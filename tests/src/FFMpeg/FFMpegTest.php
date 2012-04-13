<?php

namespace FFMpeg;

class FFMpegTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var FFMpeg
     */
    protected $object;

    /**
     * @var FFProbe
     */
    protected $probe;

    public function setUp()
    {
        $this->object = FFMpeg::load();
        $this->probe = FFProbe::load();
    }

    /**
     * @covers FFMpeg\FFMpeg::open
     * @expectedException \InvalidArgumentException
     */
    public function testOpenInvalid()
    {
        $this->object->open(__DIR__ . '/invalid.files');
    }

    /**
     * @covers FFMpeg\FFMpeg::open
     */
    public function testOpen()
    {
        $this->object->open(__DIR__ . '/../../files/Test.ogv');
    }

    /**
     * @covers FFMpeg\FFMpeg::extractImage
     */
    public function testExtractImage()
    {
        $dest = __DIR__ . '/../../files/extract_Test.jpg';

        $this->object->open(__DIR__ . '/../../files/Test.ogv');
        $this->object->extractImage(2, $dest, 200, 200);

        $this->probe->probeFormat($dest);

        unlink($dest);
    }


    /**
     * @covers FFMpeg\FFMpeg::extractImage
     * @expectedException \RuntimeException
     */
    public function testExtractImageNoMovie()
    {
        $this->object->extractImage(2, 'Path', 200, 200);
    }

    /**
     * @covers FFMpeg\FFMpeg::encode
     * @expectedException \RuntimeException
     */
    public function testEncode()
    {
        $this->object->encode(new Format\WebM(32, 32), './invalid.file');
    }

    /**
     * @covers FFMpeg\FFMpeg::encode
     * @expectedException \RuntimeException
     */
    public function testWrongBinary()
    {
        $logger = new \Monolog\Logger('test');
        $logger->pushHandler(new \Monolog\Handler\NullHandler());

        $ffmpeg = new FFMpeg('wrongbinary', $logger);
        $ffmpeg->open(__DIR__ . '/../../files/Test.ogv');
        $ffmpeg->encode(new Format\WebM(32, 32), './invalid.file');
    }

    /**
     * @covers FFMpeg\FFMpeg::encode
     */
    public function testEncodeWebm()
    {
        $dest = __DIR__ . '/../../files/encode_test.webm';

        $this->object->open(__DIR__ . '/../../files/Test.ogv');
        $this->object->encode(new Format\WebM(32, 32), $dest);

        $this->probe->probeFormat($dest);

        unlink($dest);
    }

    /**
     * @covers FFMpeg\FFMpeg::encode
     */
    public function testEncodeOgg()
    {
        $dest = __DIR__ . '/../../files/encode_test.ogv';

        $this->object->open(__DIR__ . '/../../files/Test.ogv');
        $this->object->encode(new Format\Ogg(32, 32), $dest);

        $this->probe->probeFormat($dest);

        unlink($dest);
    }

    /**
     * @covers FFMpeg\FFMpeg::encode
     */
    public function testEncodeX264()
    {
        $dest = __DIR__ . '/../../files/encode_test.mp4';

        $this->object->open(__DIR__ . '/../../files/Test.ogv');
        $this->object->encode(new Format\X264(32, 32), $dest);

        $this->probe->probeFormat($dest);

        unlink($dest);
    }

}