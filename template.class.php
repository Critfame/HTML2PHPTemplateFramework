<?php
class Template
{
    private static $configuration = [
        'base_url' => null,
        'tpl_dir' => 'templates',
        'skin' => 'default',
        'debug' => false,
        'checksum' => null
    ];

    private static $storedVariables = [];
    private static $compiledPage;

    public static function configure($setting, $value = null)
    {
        if(is_array($setting))
        {
            foreach($setting as $key => $value)
            {
                self::configure($key, $value);
            }
        }
        elseif(isset(self::$configuration[$setting]))
        {
            self::$configuration[$setting] = $value;
            // track a record of what has been changed
            self::$configuration['checksum'][$setting] = $value;
        }
    }

    public static function variables($setting, $value = null)
    {
        if(is_array($setting))
        {
            foreach($setting as $key => $value)
            {
                self::variables(strtoupper($key), $value);
            }
        }
        else
        {
            self::$storedVariables[strtoupper($setting)] = $value;
        }
    }

    function render($pageName)
    {
        $pageFileName = self::$configuration['base_url'] . self::$configuration['tpl_dir'] . '/' . $pageName . '.html';

        if(file_exists($pageFileName))
        {
            self::$compiledPage = file_get_contents($pageFileName);
        }
        else
        {
            echo '<b>Template Error</b>: File Inclusion Error.';
        }

        //Replace include
        self::$compiledPage = preg_replace_callback (
        '/\{ \@include (.*?) \}/',
            function($pageName)
            {
                    $pageFileName = self::$configuration['base_url'] . self::$configuration['tpl_dir'] . '/' . $pageName[1] . '.html';
                    if(file_exists($pageFileName))
                    {
                        return file_get_contents($pageFileName);
                    }
                    else
                    {
                        return '<b>Template Include Error</b>: File Inclusion Error.';
                    }
            },
            self::$compiledPage
        );

        //Replace vars
        foreach(self::$storedVariables as $key => $value)
        {
            self::$compiledPage = str_replace('{ $ ' . $key . ' }', $value, self::$compiledPage);
        }

        echo self::$compiledPage;

        if(self::$configuration['debug'] == true)
        {
            echo '<div class="debug"><div>Change $builder->configure(\'debug\', boolean) to turn debug off</div><hr><div>Configuration</div><span>';
            var_dump(self::$configuration);
            echo '</span><div>Stored Variables</div><span>';
            var_dump(self::$storedVariables);
            echo '</span></div>';
        }
    }
}

$builder = new Template();
?>