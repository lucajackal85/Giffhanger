# Giffhanger
It generates Video previews in GIF or Video format.
To use this library you need ffmpeg executable installed

## Usage
Minimal example
```
$giffhanger = new Giffhanger('/path/to/video/file',[
    'output_dimension' => 320
]);

$giffhanger->generateGIF('/path/to/output.gif');
$giffhanger->generateVideo('/path/to/output.avi');
```