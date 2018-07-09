<?php

namespace App\EventSubscriber;

use App\Annotation\ThrowViolations;
use App\Exception\ConstraintViolationException;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerArgumentsEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolationEventSubscriber implements EventSubscriberInterface
{

    /**
     * @var Reader
     */
    private $reader;

    /**
     * ConstraintViolationEventSubscriber constructor.
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * @param FilterControllerArgumentsEvent $event
     * @throws ConstraintViolationException
     */
    public function onKernelControllerArguments(FilterControllerArgumentsEvent $event)
    {
        if (!is_array($event->getController())) {
            return;
        }
        list($controller, $method) = $event->getController();
        if (!$controller || !$method) {
            return;
        }
        $throwViolations = $this->reader->getMethodAnnotation(new \ReflectionMethod($controller, $method), ThrowViolations::class);
        if (!$throwViolations) {
            return;
        }
        foreach ($event->getArguments() as $argument) {
            if ($argument instanceof ConstraintViolationListInterface && $argument->count()) {
                throw new ConstraintViolationException($argument);
            }
        }
    }
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [KernelEvents::CONTROLLER_ARGUMENTS => 'onKernelControllerArguments'];
    }

}