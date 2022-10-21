<?php

namespace App\EventListener;

use App\Entity\Video;

class VideoListener
{
	public function __construct()
	{
	}

	public function postLoad(Video $video): void
	{
		$video->virtual = [];

		$urlArray = parse_url(urldecode($video->getLink()));
		if (!empty($urlArray['host'])) {
			// Calcul des variables
			$domain = str_replace('www.', '', $urlArray['host']);
			if (!empty($urlArray['query'])) {
				$queryArray = [];
				$splitedArray = explode('&', $urlArray['query']);
				foreach ($splitedArray as $strQuery) {
					$tempSplited = explode('=', $strQuery);
					$queryArray[$tempSplited[0]] = $tempSplited[1];
				}
				$urlArray['arrayQuery'] = $queryArray;
			}
			if (!empty($urlArray['path'])) {
				$explodedPath = explode('/', $urlArray['path']);
				foreach ($explodedPath as $pathPoint) {
					if (!empty($pathPoint)) {
						$urlArray['arrayPath'][] = $pathPoint;
					}
				}
			}

			// Attribution des variables
			$video->virtual['domain'] = $domain;

			if (in_array($domain, ['youtube.com', 'youtu.be'])) {
				$video->virtual['video_code'] = $urlArray['arrayQuery']['v'];
				$video->virtual['thumbnail_url'] = 'https://img.youtube.com/vi/'.$urlArray['arrayQuery']['v'].'/0.jpg';
				$video->virtual['preview_url'] = 'https://www.youtube.com/embed/'.$urlArray['arrayQuery']['v'];
				$video->virtual['frame_allow'] = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
			} elseif (in_array($domain, ['vimeo.com'])) {
				$video->virtual['video_code'] = $urlArray['arrayPath'][0];
				$video->virtual['thumbnail_url'] = null;
				$video->virtual['preview_url'] = 'https://player.vimeo.com/video/'.$urlArray['arrayPath'][0];
				$video->virtual['frame_allow'] = 'autoplay; fullscreen; picture-in-picture';
			} elseif (in_array($domain, ['dailymotion.com', 'dai.ly'])) {
				$video->virtual['video_code'] = $urlArray['arrayPath'][1];
				$video->virtual['thumbnail_url'] = 'https://www.dailymotion.com/thumbnail/video/'.$urlArray['arrayPath'][1];
				$video->virtual['preview_url'] = 'https://www.dailymotion.com/embed/video/'.$urlArray['arrayPath'][1];
				$video->virtual['frame_allow'] = 'autoplay; fullscreen; picture-in-picture';
			}

			$video->virtual['frame_title'] = 'Video from '.$domain;
		}
	}
}
