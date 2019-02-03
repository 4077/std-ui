<?php namespace std\ui\controllers;

class QQFileuploader extends \Controller
{
    private function _size_limit()
    {
        if (!empty($this->data['size_limit'])) {
            $size_limit = $this->data['size_limit'];
        } else {
            $size_limit = ini_get('upload_max_filesize');
        }

        $dimension = substr($size_limit, -1);

        switch ($dimension) {
            case 'G':
                $size_limit *= 1024;
            case 'M':
                $size_limit *= 1024;
            case 'K':
                $size_limit *= 1024;
        }

        return $size_limit;
    }

    public function receive()
    {
        $upload_dir = force_r_slash(!empty($this->data['upload_dir']) ? $this->data['upload_dir'] : $this->_protected());

        mdir($upload_dir);

        $allowed_extensions = !empty($this->data['extensions']) ? l2a($this->data['extensions']) : [];
        $size_limit = $this->_size_limit();
        $random_name = !empty($this->data['random_name']) ? $this->data['random_name'] : true;
        $replace = !empty($this->data['random_name']) ? $this->data['random_name'] : false;

        //

        $uploader = new \std\ui\QqFileUploader\QqFileUploader($allowed_extensions, $size_limit);
        $uploaded_file = $uploader->handleUpload($upload_dir, $random_name, $replace);

        return new QQFileuploader_receive($uploaded_file);
    }

    //

    private $default_options = [];

    private $default_template_options = [
        'drop_area_content' => 'Сбросьте файлы чтобы начать процесс загрузки',
        'content'           => 'Нажмите или перетащите сюда файлы чтобы загрузить',
        'class'             => false,
        'droparea_class'    => false
    ];

    public function view($tag = false)
    {
        if (isset($this->data['path'])) {
            $id = k(8);

            $data = ['selector' => '#' . $id];

            $qq = $this->c('@tag', ['attrs' => ['id' => $id]]);

            // path & data

            if (isset($this->data['path'])) {
                $data['request_path'] = $this->_caller()->_p($this->data['path']);
            }

            if (isset($this->data['data'])) {
                $data['request_data'] = $this->data['data'];
            }

            // attrs

            if (isset($this->data['container_class'])) {
                $qq->attr('class', $this->data['container_class']);
            }

            // template

            $template = $this->default_template_options;
            foreach (array_keys($this->default_template_options) as $option) {
                if (isset($this->data[$option])) {
                    $template[$option] = $this->data[$option];
                }
            }

            remap($data, $this->data, 'plugin_options');

            $data['template'] = '<div class="qq-uploader">
                                   <div class="qq-upload-drop-area ' . $template['droparea_class'] . '"><span>' . $template['drop_area_content'] . '</span></div>
                                   <div class="qq-upload-button ' . $template['class'] . '" hover="hover">' . $template['content'] . '</div>
                                   <ul class="qq-upload-list"></ul>
                                   </div>';

            //

            $this->css('@_plugins/qqfileuploader/qqfileuploader');
            $this->js('@_plugins/qqfileuploader/qqfileuploader');
            $this->js(':std_ui_qqfileuploader', $data);

            return $qq->view($tag);
        }
    }
}

class QQFileuploader_receive
{
    public $path;
    public $name;
    public $ext;

    /*

    error_codes:

    1 - нет папки или нет прав на запись
    2 - файл не загрузился
    3 - пустой файл
    4 - размер файла больше ограничения
    5 - не разрешенное разрешение
    6 - непоянтная ошибка

    */

    public $error_code;
    public $error;

    public function __construct($file)
    {
        if (!isset($file['error'])) {
            $this->path = $file['path'];
            $this->name = $file['name'];
            $this->ext = $file['ext'];
        } else {
            $this->error_code = isset($file['error_code']) ? $file['error_code'] : 0;
            $this->error = $file['error'];
        }
    }

    public function remove()
    {
        @unlink($this->path);
    }
}