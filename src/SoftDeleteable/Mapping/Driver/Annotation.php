<?php

namespace EvozonPhp\SoftDeleteable\Mapping\Driver;

use EvozonPhp\SoftDeleteable\Mapping\Validator;
use Gedmo\Mapping\Driver\AbstractAnnotationDriver;

/**
 * This is an annotation mapping driver for SoftDeleteable
 * behavioral extension. Used for extraction of extended
 * metadata from Annotations specifically for SoftDeleteable
 * extension.
 *
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class Annotation extends AbstractAnnotationDriver
{
    /**
     * Annotation to define that this object is loggable.
     */
    const SOFT_DELETEABLE = 'Gedmo\\Mapping\\Annotation\\SoftDeleteable';

    /**
     * {@inheritdoc}
     */
    public function readExtendedMetadata($meta, array &$config)
    {
        $class = $this->getMetaReflectionClass($meta);
        // class annotations
        if (null !== $class && $annot = $this->reader->getClassAnnotation($class, self::SOFT_DELETEABLE)) {
            $config['softDeleteable'] = true;

            Validator::validateField($meta, $annot->fieldName);

            $config['fieldName'] = $annot->fieldName;
        }

        $this->validateFullMetadata($meta, $config);
    }
}
