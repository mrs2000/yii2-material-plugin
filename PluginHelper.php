<?php

namespace mrssoft\plugins;

class PluginHelper
{
    /**
     * @param string $text
     * @return PluginInfo[]
     */
    public static function parseCode(string $text): array
    {
        $result = [];
        if (preg_match_all('/{plugin:(.+)(\[(.+)])?}/mU', $text, $matches)) {
            foreach ($matches[1] as $n => $plugin) {
                $info = new PluginInfo($plugin, $matches[0][$n]);
                if ($matches[3][$n]) {
                    foreach (explode(',', $matches[3][$n]) as $p) {
                        $t = explode('=', $p);
                        $info->addParams($t[0], $t[1]);
                    }
                }
                $result[] = $info;
            }
        }

        return $result;
    }
}