<?php

namespace App\Services;

use Core\Constants\Constants;
use Core\Database\ActiveRecord\Model;
use Core\Database\Database;
use PDO;
use function var_dump;

class ImageUpload
{
  /** @var array<string, mixed> $image */
  private array $image;

  public function __construct(
    private Model $model
  ) {
  }

  public function path(): string
  {
    if ($this->model->photo) {
      // Generate MD5 hash of the avatar file to use as cache buster in URL
      $hash = md5_file($this->getAbsoluteSavedFilePath());
      // Return the avatar URL with hash parameter to force browser to reload when file changes
      return $this->baseDir() . $this->model->photo . '?' . $hash;
    }

    return "/assets/images/defaults/photo.png";
  }

  /**
   * @param array<string, mixed> $image
   */
  public function update(array $image): void
  {
    $this->image = $image;

    if (!empty($this->getTmpFilePath())) {
      $pdo = Database::getDatabaseConn();
      $pdo->beginTransaction();

      try {
        $this->removeOldImage();

        if (!move_uploaded_file($this->getTmpFilePath(), $this->getAbsoluteDestinationPath())) {
          throw new \RuntimeException('Failed to move uploaded file');
        }

        $this->model->update(['photo' => $this->getFileName()]);

        $pdo->commit();
      } catch (\Exception $e) {
        $pdo->rollBack();
        throw $e;
      }
    }
  }

  private function getTmpFilePath(): string
  {
    return $this->image['tmp_name'];
  }

  private function removeOldImage(): void
  {
    if ($this->model->photo) {
      $path = Constants::rootPath()->join('public' . $this->baseDir())->join($this->model->photo);
      unlink($this->getAbsoluteSavedFilePath());
    }
  }

  private function getFileName(): string
  {
    $file_name_splitted  = explode('.', $this->image['name']);
    $file_extension = end($file_name_splitted);
    return 'avatar.' . $file_extension;
  }

  private function getAbsoluteDestinationPath(): string
  {
    return $this->storeDir() . $this->getFileName();
  }

  private function baseDir(): string
  {

    return "/assets/uploads/{$this->model::table()}/{$this->model->id}/";
  }

  private function storeDir(): string
  {
    $path = Constants::rootPath()->join('public' . $this->baseDir());

    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }

    return $path;
  }

  private function getAbsoluteSavedFilePath(): string
  {
    return Constants::rootPath()->join('public' . $this->baseDir())->join($this->model->photo);
  }
}
