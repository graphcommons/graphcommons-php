<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Graph Commons & contributors.
 *     <http://graphcommons.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */
declare (strict_types=1);

namespace GraphCommons;

/**
 * @package GraphCommons
 * @object  GraphCommons\Autoload
 * @author  Kerem Güneş <k-gun@mail.com>
 */
final /* static */ class Autoload
{
    public static function register(): bool
    {
        return spl_autoload_register(function ($objectName) {
            if ($objectName[0] != '\\') {
                $objectName = '\\'. $objectName;
            }

            // only self files
            if (1 !== strpos($objectName, __namespace__)) {
                return;
            }

            // prepare file name & path
            $objectName = substr($objectName, 1 + strlen(__namespace__));
            $objectFile = sprintf('%s/%s.php', __dir__, str_replace('\\', '/', $objectName));

            // check file is exists
            if (!is_file($objectFile)) {
                throw new \RuntimeException(sprintf(
                    'Object file not found! object name(%s) file name(%s)',
                    $objectName, $objectFile
                ));
            }

            require $objectFile;
        });
    }
}
