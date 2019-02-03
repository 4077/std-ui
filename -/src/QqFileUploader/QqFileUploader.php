<?php namespace std\ui\qqFileUploader;

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr
{
    /**
     * Save the file to the specified path
     *
     * @return boolean TRUE on success
     */
    function save($path)
    {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != $this->getSize()) {
            return false;
        }

        $target = fopen($path, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }

    function getName()
    {
        return $_GET['qqfile'];
    }

    function getSize()
    {
        if (isset($_SERVER["CONTENT_LENGTH"])) {
            return (int)$_SERVER["CONTENT_LENGTH"];
        } else {
            throw new Exception('Getting content length is not supported.');
        }
    }
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm
{
    /**
     * Save the file to the specified path
     *
     * @return boolean TRUE on success
     */
    function save($path)
    {
        if (!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)) {
            return false;
        }

        return true;
    }

    function getName()
    {
        return $_FILES['qqfile']['name'];
    }

    function getSize()
    {
        return $_FILES['qqfile']['size'];
    }
}

class qqFileUploader
{
    private $allowedExtensions = [];
    private $sizeLimit = 10485760;
    private $file;

    function __construct(array $allowedExtensions = [], $sizeLimit = 10485760)
    {
        $allowedExtensions = array_map("strtolower", $allowedExtensions);

        $this->allowedExtensions = $allowedExtensions;
        $this->sizeLimit = $sizeLimit;

        $this->checkServerSettings();

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else {
            $this->file = false;
        }
    }

    private function checkServerSettings()
    {
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));

        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit) {
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");
        }
    }

    private function toBytes($str)
    {
        $val = trim($str);
        $last = strtolower($str[strlen($str) - 1]);

        switch ($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }

    /**
     * Returns array('success'=>true) or array('error'=>'error message')
     */
    function handleUpload($uploadDirectory, $randomName = true, $replaceOldFile = false)
    {
        if (!is_writable($uploadDirectory)) {
            return [
                'error_code' => 1,
                'error'      => 'Server error. Upload directory isn\'t writable.'
            ];
        }

        if (!$this->file) {
            return [
                'error_code' => 2,
                'error'      => 'No files were uploaded.'
            ];
        }

        $size = $this->file->getSize();

        if ($size == 0) {
            return [
                'error_code' => 3,
                'error'      => 'File is empty'
            ];
        }

        if ($size > $this->sizeLimit) {
            return [
                'error_code' => 4,
                'error'      => 'File is too large'
            ];
        }

        $pathinfo = pathinfo($this->file->getName());
        $source_name = $pathinfo['filename'];
        $ext = $pathinfo['extension'];

        if ($randomName) {
            $save_name = md5(uniqid());
        } else {
            $save_name = $source_name;
        }

        if ($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)) {
            $these = implode(', ', $this->allowedExtensions);

            return [
                'error_code' => 5,
                'error'      => 'File has an invalid extension, it should be one of ' . $these . '.'
            ];
        }

        if (!$randomName && !$replaceOldFile) {
            while (file_exists($uploadDirectory . $save_name . '.' . $ext)) {
                $save_name .= rand(10, 99);
            }
        }

        if ($this->file->save($uploadDirectory . $save_name . '.' . $ext)) {
            return [
                'path' => $uploadDirectory . $save_name . '.' . $ext,
                'name' => $source_name,
                'ext'  => $ext
            ];
        } else {
            return [
                'error_code' => 6,
                'error'      => 'Could not save uploaded file. The upload was cancelled, or server error encountered'
            ];
        }
    }
}