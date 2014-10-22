<?php
use Peridot\Plugin\Yo\Request;

describe("Request", function() {

    beforeEach(function() {
        $this->users = ['user1', 'user2'];
        $this->token = 'mytoken';
        $this->request = new Request($this->token, $this->users);
    });

    describe('->getQueries()', function() {
        it('should return a query ready for post for each user', function() {
            $queries = $this->request->getQueries();
            assert(count($queries) == 2, "should return 2 queries");
            for ($i = 0; $i < count($this->users); $i++) {
                $query = $queries[$i];
                $user = $this->users[$i];
                $expected = http_build_query(['api_token' => $this->token, 'username' => $user]);
                assert($query == $expected, "expected $expected got $query");
            }
        });
    });

    describe("->setLink()", function() {

        beforeEach(function() {
            $this->link = "http://buildserver.peridot.com";
        });

        context("when passing in a string", function() {
           it("should build the query with the string", function() {
               $this->request->setLink($this->link);
               $query = $this->request->getQueries()[0];
               $expected = http_build_query(['api_token' => $this->token, 'username' => $this->users[0], 'link' => $this->link]);
               assert($query == $expected, "expected $expected got $query");
           });
        });

        context("when passing in a function", function() {
            it("should build the query with the result of the function", function() {
                $fn = function() { return $this->link; };
                $this->request->setLink($fn);
                $query = $this->request->getQueries()[0];
                $expected = http_build_query(['api_token' => $this->token, 'username' => $this->users[0], 'link' => $this->link]);
                assert($query == $expected, "expected $expected got $query");
            });
        });
    });

    describe("->getContextOptions()", function() {
        it("should return an array of arrays fit for a stream context", function() {
            $expected = [];
            foreach ($this->users as $user) {
                $expected[] = array(
                    'http' => array(
                        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method'  => 'POST',
                        'content' => http_build_query(['api_token' => $this->token, 'username' => $user])
                    )
                );
            }
            $options = $this->request->getContextOptions();
            assert($options == $expected, "context options cant be used in stream context");
        });
    });

    describe("->getData()", function() {
       it('should return an array of data for a yo request', function() {
           $data = $this->request->getData('brian');
           $expected = ['api_token' => $this->token, 'username' => 'brian'];
           assert($data == $expected, "data should contain token and username");
       });
    });

});
