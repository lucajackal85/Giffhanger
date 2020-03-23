<?php


namespace Jackal\Giffhanger\Giffhanger;

use Jackal\Giffhanger\Configuration\Configuration;
use Jackal\Giffhanger\Exception\GiffhangerException;
use Jackal\Giffhanger\Generator\GifGenerator;
use Jackal\Giffhanger\Generator\VideoMP4Generator;

class Giffhanger
{
    /**
     * @var string
     */
    protected $videoFile;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * Giffhanger constructor.
     * @param $sourceVideoFile
     * @param array $config
     * @throws \Jackal\Giffhanger\Exception\GiffhangerConfigurationException
     */
    public function __construct($sourceVideoFile, $config = [])
    {
        $this->videoFile = $sourceVideoFile;

        $this->config  = new Configuration($config);
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param $destinationFile
     * @throws GiffhangerException
     */
    public function generate($destinationFile)
    {
        $ext = strtolower(pathinfo($destinationFile, PATHINFO_EXTENSION));
        switch ($ext) {
            case 'gif':
                $this->generateGIF($destinationFile);
                break;
            case 'avi':
            case 'mp4':
                $this->generateVideo($destinationFile);
                break;
            default:
                throw GiffhangerException::invalidExtension($ext);
        }
    }

    /**
     * @param $destinationGIFFile
     * @throws \Exception
     */
    protected function generateGIF($destinationGIFFile)
    {
        $generator = new GifGenerator($this->videoFile, $destinationGIFFile, $this->config);
        $generator->generate();
    }

    /**
     * @param $destinationVideoFile
     * @throws \Exception
     */
    protected function generateVideo($destinationVideoFile)
    {
        $generator = new VideoMP4Generator($this->videoFile, $destinationVideoFile, $this->config);
        $generator->generate();
    }
}
