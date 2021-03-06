<?php

/**
 * The namespaces are funky in this file in order to properly
 * test CommandInterface::getCommandName() with having to rely
 * on any external Commands being created.
 */

namespace Testing\CommandTest {

    class SimpleCommand extends \TomWright\Commander\Command\Command
    {

        /**
         * @var bool
         */
        protected $executed;


        /**
         * @return bool
         */
        public function getExecuted()
        {
            return $this->executed;
        }


        /**
         * @param bool $executed
         */
        public function setExecuted($executed)
        {
            $this->executed = $executed;
        }

    }

    class SimpleHandler implements \TomWright\Commander\Handler\HandlerInterface
    {

        /**
         * @param SimpleCommand $command
         * @return mixed
         */
        public function handle(\TomWright\Commander\Command\CommandInterface $command)
        {
            $command->setExecuted(true);

            $command->setSuccessful(true);
        }
    }

}

namespace {

    class CommandTest extends PHPUnit_Framework_TestCase
    {

        public function testGetCommandName()
        {
            $command = new \Testing\CommandTest\SimpleCommand();
            $name = $command->getCommandName();
            $this->assertEquals('Simple', $name);
        }

        public function testHandlerIsExecuted()
        {
            $bus = \TomWright\Commander\CommandBus::getInstance();
            $bus->addHandlerNamespace('\\Testing\\CommandTest\\');

            $command = new \Testing\CommandTest\SimpleCommand();

            $command->setExecuted(false);
            $this->assertFalse($command->getExecuted());

            $bus->handle($command);

            $this->assertTrue($command->getExecuted());
        }

        public function testCommandWasSuccessful()
        {
            $bus = \TomWright\Commander\CommandBus::getInstance();
            $bus->addHandlerNamespace('\\Testing\\CommandTest\\');

            $command = new \Testing\CommandTest\SimpleCommand();

            $this->assertFalse($command->wasSuccessful());

            $bus->handle($command);

            $this->assertTrue($command->wasSuccessful());
        }

    }

}