<?php

namespace PUGX\Poser\UI;

use PUGX\Poser\Render\SvgFlatRender;
use PUGX\Poser\Render\SvgFlatSquareRender;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use PUGX\Poser\Badge;
use PUGX\Poser\Poser;
use PUGX\Poser\Render\SvgRender;

class Command extends BaseCommand
{

    CONST HEADER = "                   ________________
 <bg=black;options=reverse> |_  _  _| _  _   </bg=black;options=reverse>  _  _  _ _  _  |
 <bg=black;options=reverse> |_)(_|(_|(_|(/_  </bg=black;options=reverse> |_)(_)_\(/_|   |
 <bg=black;options=reverse>           _|     </bg=black;options=reverse>_|______________|

 http://poser.pug.org
";

    /** @var Poser */
    private $poser;

    /** @var string */
    protected $format;

    /** @var string */
    protected $header;

    private function init()
    {
        $this->poser = new Poser(array(
            new SvgRender(),
            new SvgFlatRender(),
            new SvgFlatSquareRender()
        ));
        $this->format = 'flat';
        $this->header = self::HEADER;
    }

    protected function configure()
    {
        $this->init();

        $this
            ->setName('generate')
            ->setDescription('Create a badge you are a Poser.')
            ->addArgument(
                'subject',
                InputArgument::OPTIONAL,
                'The subject eg. `license`'
            )
            ->addArgument(
                'status',
                InputArgument::OPTIONAL,
                'The status example `MIT`'
            )
            ->addArgument(
                'color',
                InputArgument::OPTIONAL,
                'The hexadecimal color eg. `97CA00` or the name ['.join(', ', Badge::getColorNamesAvailable()).']'
            )
            ->addOption(
                'format',
                'f',
                InputOption::VALUE_REQUIRED,
                'The format of the image eg. `svg`, formats available ['.join(', ', $this->poser->validFormats()).']'
            )
            ->addOption(
                'path',
                'p',
                InputOption::VALUE_REQUIRED,
                'The path of the file to save the create eg. `/tmp/license.svg`'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $subject = $input->getArgument('subject');
        $status = $input->getArgument('status');
        $color = $input->getArgument('color');

        if ($input->getOption('format')) {
            $this->format = $input->getOption('format');
        }

        try {
            $imageContent = $this->poser->generate($subject, $status, $color, $this->format);

            if ($input->getOption('path')) {
                $this->storeImage($output, $input->getOption('path'), $imageContent);
            } else {
                $this->flushImage($output, $imageContent);
            }

        } catch (\Exception $e) {
            $this->printHeaderOnce($output);
            throw $e;
        }
    }

    protected function flushImage(OutputInterface $output, $imageContent)
    {
        $output->write((string) $imageContent);
        $this->header = '';
    }

    protected function storeImage(OutputInterface $output, $path, $imageContent)
    {
        $this->printHeaderOnce($output);
        try {
            $fp = @fopen($path, "x");
        } catch (\Exception $e) {
            $fp = false;
        }

        if (false == $fp) {
           throw new \Exception("Error on creating the file maybe file [$path] already exists?");
        }
        $written = @fwrite($fp, $imageContent);
        if ($written <1 || $written != strlen($imageContent)) {
            throw new \Exception('Error on writing to file.');
        }
        @fclose($fp);

        $output->write(sprintf('Image created at %s', $path));
    }

    protected function printHeaderOnce(OutputInterface $output)
    {
        if (!empty($this->header)) {
            $output->write($this->header);
        }

        $this->header = '';
    }
}
