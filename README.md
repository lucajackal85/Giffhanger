# Giffhanger
[![Latest Stable Version](https://poser.pugx.org/jackal/giffhanger/v/stable)](https://packagist.org/packages/jackal/giffhanger)
[![Total Downloads](https://poser.pugx.org/jackal/giffhanger/downloads)](https://packagist.org/packages/jackal/giffhanger)
[![Latest Unstable Version](https://poser.pugx.org/jackal/giffhanger/v/unstable)](https://packagist.org/packages/jackal/giffhanger)
[![License](https://poser.pugx.org/jackal/giffhanger/license)](https://packagist.org/packages/jackal/giffhanger)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lucajackal85/Giffhanger/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lucajackal85/Giffhanger/?branch=master)
[![Build Status](https://travis-ci.org/lucajackal85/Giffhanger.svg?branch=master)](https://travis-ci.org/lucajackal85/Giffhanger)
#### (play on words: from ["Cliffhanger"](https://en.wikipedia.org/wiki/Cliffhanger))
It generates Video previews in GIF or Video format.
To use this library you need ffmpeg executable installed

## Installation
```
composer require jackal/giffhanger
```
## Usage
Minimal example
```
$giffhanger = new Giffhanger('/path/to/video/file',[
    'output_dimension' => 320
]);
//to generate gif
$giffhanger->generate('/path/to/output.gif');

//to generate video
$giffhanger->generate('/path/to/output.avi');
```

## Options
- **resize_width**: resize width (in pixels) of the output file [default: **640**]
- **crop_ratio**: crop the video following the value passed [default crop is not applied]
- **temp_dir**: define the temp directory used to generate the output file [default is **system temp directory**]
- **frames**: number of "pieces" taken to build the preview [default is **3**]
- **duration**: duration (in seconds) of the preview [default is **6**]
- **bitrate**: bitrate of the video (used only in case of mp4 output) [default **600**]
- **frame_rate**: framerate of the output [default **10**]
