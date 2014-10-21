<?php
namespace Peridot\Plugin\Yo;

use Evenement\EventEmitterInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class YoPlugin
{
    const BEHAVIOR_ON_FAIL = 1;

    const BEHAVIOR_ON_PASS = 2;

    const BEHAVIOR_ALWAYS = 3;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->behavior = static::BEHAVIOR_ON_FAIL;
    }

    /**
     * @param $exitCode
     */
    public function onPeridotEnd($exitCode, InputInterface $input, OutputInterface $output)
    {
        $hasFailed = $exitCode > 0;
        $output->writeln("  <comment>YO! Happening now</comment>");
        switch ($this->behavior) {
            case static::BEHAVIOR_ON_FAIL:
                if ($hasFailed) {
                    $this->request->yo();
                }
                break;
            case static::BEHAVIOR_ON_PASS:
                if (!$hasFailed) {
                    $this->request->yo();
                }
                break;
            default:
                $this->request->yo();
                break;
        }
    }

    /**
     * @param $behavior
     */
    public function setBehavior($behavior)
    {
        $this->behavior = $behavior;
    }

    /**
     * Register the Yo plugin
     *
     * @param EventEmitterInterface $emitter
     * @param $token
     * @param array $users
     * @param null $link
     * @return static
     */
    public static function register(EventEmitterInterface $emitter, $token, array $users, $link = null)
    {
        $request = new Request($token, $users);
        if (!is_null($link)) {
            $request->setLink($link);
        }
        $plugin = new static($request);
        $emitter->on('peridot.end', [$plugin, 'onPeridotEnd']);
        return $plugin;
    }
}
