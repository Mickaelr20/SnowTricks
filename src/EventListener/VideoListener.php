<?php

namespace App\EventListener;

use App\Entity\Video;
use Doctrine\ORM\Event\LifecycleEventArgs;

class VideoListener
{

    function __construct()
    {
    }

    public function postLoad(Video $video): void
    {
        $video->virtual = [];

        $url_arr = parse_url(urldecode($video->getLink()));
        if (!empty($url_arr['host'])) {
            // Calcul des variables
            $domain = str_replace('www.', '', $url_arr['host']);
            if (!empty($url_arr['query'])) {
                $query_arr = [];
                $splited_query = explode('&', $url_arr['query']);
                foreach ($splited_query as $str_query) {
                    $temp_splited = explode('=', $str_query);
                    $query_arr[$temp_splited[0]] = $temp_splited[1];
                }
                $url_arr['array_query'] = $query_arr;
            }
            if (!empty($url_arr['path'])) {
                $exploded_path = explode('/', $url_arr['path']);
                foreach ($exploded_path as $path_point) {
                    if (!empty($path_point)) {
                        $url_arr['array_path'][] = $path_point;
                    }
                }
            }

            // Attribution des variables
            $video->virtual['domain'] = $domain;

            if (in_array($domain, ['youtube.com', 'youtu.be'])) {
                $video->virtual['video_code'] = $url_arr['array_query']['v'];
                $video->virtual['thumbnail_url'] = "https://img.youtube.com/vi/" . $url_arr['array_query']['v'] . "/0.jpg";
                $video->virtual["preview_url"] = "https://www.youtube.com/embed/" . $url_arr['array_query']['v'];
                $video->virtual["frame_allow"] = "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture";
            } else if (in_array($domain, ['vimeo.com'])) {
                $video->virtual['video_code'] = $url_arr['array_path'][0];
                $video->virtual['thumbnail_url'] = null;
                $video->virtual["preview_url"] = "https://player.vimeo.com/video/" . $url_arr['array_path'][0];
                $video->virtual["frame_allow"] = "autoplay; fullscreen; picture-in-picture";
            } else if (in_array($domain, ['dailymotion.com', 'dai.ly'])) {
                $video->virtual['video_code'] = $url_arr['array_path'][1];
                $video->virtual['thumbnail_url'] = "https://www.dailymotion.com/thumbnail/video/" . $url_arr['array_path'][1];
                $video->virtual["preview_url"] = "https://www.dailymotion.com/embed/video/" . $url_arr['array_path'][1];
                $video->virtual["frame_allow"] = "autoplay; fullscreen; picture-in-picture";
            }

            $video->virtual["frame_title"] = "Video from " . $domain;
        }
    }
}
