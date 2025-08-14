<?php
// 代码生成时间: 2025-08-15 00:34:59
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Imagine\Exception\InvalidArgumentException;
use Imagine\Image\Mode;
use Imagine\Image\Color;

// 图片尺寸批量调整器
class ImageSizeAdjuster {
    // 调整图片尺寸的方法
    public function adjustImageSize(UploadedFile $image, $targetWidth, $targetHeight) {
        try {
            // 实例化Imagine对象
            $imagine = new Imagine();
            // 获取图片信息
            $imageInfo = $image->getClientOriginalName();
            $imagePath = $image->getPathname();

            // 读取图片
            $image = $imagine->open($imagePath);
            // 设置目标尺寸
            $size = new Box($targetWidth, $targetHeight);
            // 保持图片比例进行缩放
            $image->resize($size);
            // 保存调整后的图片
            $image->save($imagePath);

            return 'Image resized successfully.';
        } catch (InvalidArgumentException $e) {
            return 'Error resizing image: ' . $e->getMessage();
        } catch (Exception $e) {
            return 'An error occurred: ' . $e->getMessage();
        }
    }

    // 处理批量图片尺寸调整
    public function batchAdjustImageSize(Request $request) {
        $images = $request->files->get('images');
        if (!$images) {
            return 'No images provided.';
        }

        foreach ($images as $image) {
            if ($image instanceof UploadedFile) {
                $targetWidth = $request->request->get('targetWidth');
                $targetHeight = $request->request->get('targetHeight');
                $result = $this->adjustImageSize($image, $targetWidth, $targetHeight);
                echo $result . '\
';
            }
        }
    }
}

// 使用示例
// $adjuster = new ImageSizeAdjuster();
// $adjuster->batchAdjustImageSize($request);
