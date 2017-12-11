<?php

namespace EvozonPhp\SoftDeleteable;

use Doctrine\Common\EventArgs;
use Gedmo\Mapping\MappedEventSubscriber;

/**
 * SoftDeleteable listener.
 *
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @author Constantin Bejenaru <constantin.bejenaru@evozon.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class SoftDeleteableListener extends MappedEventSubscriber
{
    /**
     * Pre soft-delete event.
     *
     * @var string
     */
    const PRE_SOFT_DELETE = 'preSoftDelete';

    /**
     * Post soft-delete event.
     *
     * @var string
     */
    const POST_SOFT_DELETE = 'postSoftDelete';

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [
            'loadClassMetadata',
            'onFlush',
        ];
    }

    /**
     * If it's a SoftDeleteable object, update the "deletedAt" field
     * and skip the removal of the object.
     *
     * @param EventArgs $args
     */
    public function onFlush(EventArgs $args)
    {
        $ea = $this->getEventAdapter($args);
        $om = $ea->getObjectManager();
        $uow = $om->getUnitOfWork();
        $evm = $om->getEventManager();

        //getScheduledDocumentDeletions
        foreach ($ea->getScheduledObjectDeletions($uow) as $object) {
            $meta = $om->getClassMetadata(get_class($object));
            $config = $this->getConfiguration($om, $meta->name);

            if (isset($config['softDeleteable']) && $config['softDeleteable']) {
                $reflProp = $meta->getReflectionProperty($config['fieldName']);
                $oldValue = $reflProp->getValue($object);
                if (true === $oldValue) {
                    continue; // want to hard delete
                }

                $evm->dispatchEvent(
                    self::PRE_SOFT_DELETE,
                    $ea->createLifecycleEventArgsInstance($object, $om)
                );

                $newValue = true;
                $reflProp->setValue($object, $newValue);

                $om->persist($object);
                $uow->propertyChanged($object, $config['fieldName'], $oldValue, $newValue);

                $uow->scheduleExtraUpdate($object, [
                    $config['fieldName'] => [$oldValue, $newValue],
                ]);

                $evm->dispatchEvent(
                    self::POST_SOFT_DELETE,
                    $ea->createLifecycleEventArgsInstance($object, $om)
                );
            }
        }
    }

    /**
     * Maps additional metadata.
     *
     * @param EventArgs $eventArgs
     */
    public function loadClassMetadata(EventArgs $eventArgs)
    {
        $ea = $this->getEventAdapter($eventArgs);
        $this->loadMetadataForObjectClass($ea->getObjectManager(), $eventArgs->getClassMetadata());
    }

    /**
     * {@inheritdoc}
     */
    protected function getNamespace()
    {
        return __NAMESPACE__;
    }
}
