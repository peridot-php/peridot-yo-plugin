<?php
use Peridot\Plugin\Yo\Request;
use Peridot\Plugin\Yo\YoPlugin;

describe('YoPlugin', function() {
    beforeEach(function() {
        $this->request = new StubRequest("token", ['user1']);
        $this->plugin = new YoPlugin($this->request);
    });

    describe('->onPeridotEnd()', function() {
        context("when behavior is set to yo on fail", function() {
            it("should yo when exit code is greater than 0", function() {
                $this->plugin->onPeridotEnd(1);
                assert($this->request->itDidYo(), "it should have yo'ed on failure");
            });

            it("should not yo when exit code is 0", function() {
                $this->plugin->onPeridotEnd(0);
                assert(!$this->request->itDidYo(), "it not should have yo'ed on pass");
            });
        });

        context("when behavior is set to yo on pass", function() {

            beforeEach(function() {
               $this->plugin->setBehavior(YoPlugin::BEHAVIOR_ON_PASS);
            });

            it("should yo when exit code is 0", function() {
                $this->plugin->onPeridotEnd(0);
                assert($this->request->itDidYo(), "it should have yo'ed on pass");
            });

            it("should not yo when exit code is greater than 0", function() {
                $this->plugin->onPeridotEnd(1);
                assert(!$this->request->itDidYo(), "it not should have yo'ed on failure");
            });
        });

        context("when behavior is set to yo always", function() {

            beforeEach(function() {
                $this->plugin->setBehavior(YoPlugin::BEHAVIOR_ALWAYS);
            });

            it("should yo when exit code is 0", function() {
                $this->plugin->onPeridotEnd(0);
                assert($this->request->itDidYo(), "it should have yo'ed on pass");
            });

            it("should yo when exit code is greater than 0", function() {
                $this->plugin->onPeridotEnd(1);
                assert($this->request->itDidYo(), "it should have yo'ed on fail");
            });
        });
    });
});

class StubRequest extends Request
{
    protected $didYo = false;

    public function yo()
    {
        $this->didYo = true;
    }

    public function itDidYo()
    {
        return $this->didYo;
    }
}
