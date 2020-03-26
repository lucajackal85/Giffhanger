<?php

namespace Jackal\Giffhanger\Giffhanger;

use Jackal\Giffhanger\Configuration\Configuration;
use Jackal\Giffhanger\Exception\GiffhangerException;
use Jackal\Giffhanger\Generator\GifGenerator;
use Jackal\Giffhanger\Generator\VideoH264Generator;
use Jackal\Giffhanger\Generator\VideoOggGenerator;
use Jackal\Giffhanger\Generator\VideoWebMGenerator;

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
     * @param string $sourceVideoFile
     * @param array $config
     * @throws \Jackal\Giffhanger\Exception\GiffhangerConfigurationException
     */
    public function __construct($sourceVideoFile, $config = [])
    {
        $this->videoFile = $sourceVideoFile;

        $this->config = new Configuration($config);
    }

    /**
     * @return Configuration
     */
    public function getConfig() : Configuration
    {
        return $this->config;
    }

    /**
     * @param string $destinationFile
     * @throws GiffhangerException
     */
    public function generate($destinationFile) : void
    {
        $ext = strtolower(pathinfo($destinationFile, PATHINFO_EXTENSION));
        switch ($ext) {
            case 'gif':
                $generator = new GifGenerator($this->videoFile, $destinationFile, $this->config);

                break;
            case 'avi':
            case 'mp4':
                $generator = new VideoH264Generator($this->videoFile, $destinationFile, $this->config);

                break;
            case 'webm':
                $generator = new VideoWebMGenerator($this->videoFile, $destinationFile, $this->config);

                break;
            case 'ogg':
                $generator = new VideoOggGenerator($this->videoFile, $destinationFile, $this->config);

                break;
            default:
                throw GiffhangerException::invalidExtension($ext);
        }

        $generator->generate();
    }
}
