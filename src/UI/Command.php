<?php

namespace PUGX\Poser\UI;

use PUGX\Poser\Badge;
use PUGX\Poser\Poser;
use PUGX\Poser\Render\SvgFlatRender;
use PUGX\Poser\Render\SvgFlatSquareRender;
use PUGX\Poser\Render\SvgPlasticRender;
use PUGX\Poser\ValueObject\InputRequest;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends BaseCommand
{
    public const HEADER = "                   ________________
 <bg=black;options=reverse> |_  _  _| _  _   </bg=black;options=reverse>  _  _  _ _  _  |
 <bg=black;options=reverse> |_)(_|(_|(_|(/_  </bg=black;options=reverse> |_)(_)_\(/_|   |
 <bg=black;options=reverse>           _|     </bg=black;options=reverse>_|______________|

 http://poser.pug.org
";

    private Poser $poser;

    protected string $header;

    private function init(): void
    {
        $this->poser = new Poser([
            new SvgPlasticRender(),
            new SvgFlatRender(),
            new SvgFlatSquareRender(),
        ]);
        $this->header = self::HEADER;
    }

    protected function configure(): void
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
                'The hexadecimal color eg. `97CA00` or the name [' . \implode(', ', Badge::getColorNamesAvailable()) . ']'
            )
            ->addOption(
                'style',
                's',
                InputOption::VALUE_REQUIRED,
                'The style of the image eg. `flat`, styles available [' . \implode(', ', $this->poser->validStyles()) . ']',
                Badge::DEFAULT_STYLE
            )
            ->addOption(
                'format',
                'f',
                InputOption::VALUE_REQUIRED,
                'The format of the image eg. `svg`, formats available [' . \implode(', ', $this->poser->validStyles()) . ']',
                Badge::DEFAULT_FORMAT
            )
            ->addOption(
                'path',
                'p',
                InputOption::VALUE_REQUIRED,
                'The path of the file to save the create eg. `/tmp/license.svg`'
            );
    }

    /**
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputRequest = InputRequest::createFromInput($input);

        try {
            $imageContent = $this->poser->generate(
                $inputRequest->getSubject(),
                $inputRequest->getStatus(),
                $inputRequest->getColor(),
                $inputRequest->getStyle(),
                $inputRequest->getFormat()
            );

            $path = $input->getOption('path');
            if ($path && \is_string($path)) {
                $this->storeImage($output, $path, (string) $imageContent);
            } else {
                $this->flushImage($output, (string) $imageContent);
            }
        } catch (\Exception $e) {
            $this->printHeaderOnce($output);
            throw $e;
        }

        return 0;
    }

    protected function flushImage(OutputInterface $output, string $imageContent): void
    {
        $output->write($imageContent);
        $this->header = '';
    }

    /**
     * @throws \Exception
     */
    protected function storeImage(OutputInterface $output, string $path, string $imageContent): void
    {
        $this->printHeaderOnce($output);
        try {
            $fp = @\fopen($path, 'x');
        } catch (\Exception $e) {
            $fp = false;
        }

        if (false == $fp) {
            throw new \Exception("Error on creating the file maybe file [$path] already exists?");
        }
        $written = @\fwrite($fp, $imageContent);
        if ($written < 1 || $written != \strlen($imageContent)) {
            throw new \Exception('Error on writing to file.');
        }
        @\fclose($fp);

        $output->write(\sprintf('Image created at %s', $path));
    }

    protected function printHeaderOnce(OutputInterface $output): void
    {
        if (!empty($this->header)) {
            $output->write($this->header);
        }

        $this->header = '';
    }
}
