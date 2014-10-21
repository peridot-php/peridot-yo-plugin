<?php
namespace Peridot\Plugin\Yo;

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
    public function onPeridotEnd($exitCode)
    {
        $hasFailed = $exitCode > 0;
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
}
