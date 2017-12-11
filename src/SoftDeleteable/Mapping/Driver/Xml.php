<?php

namespace EvozonPhp\SoftDeleteable\Mapping\Driver;

use EvozonPhp\SoftDeleteable\Mapping\Validator;
use Gedmo\Exception\InvalidMappingException;
use Gedmo\Mapping\Driver\Xml as BaseXml;

/**
 * This is a xml mapping driver for SoftDeleteable
 * behavioral extension. Used for extraction of extended
 * metadata from xml specifically for SoftDeleteable
 * extension.
 *
 * @author Gustavo Falco <comfortablynumb84@gmail.com>
 * @author Gediminas Morkevicius <gediminas.morkevicius@gmail.com>
 * @author Miha Vrhovnik <miha.vrhovnik@gmail.com>
 * @author Constantin Bejenaru <constantin.bejenaru@evozon.com>
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
class Xml extends BaseXml
{
    /**
     * {@inheritdoc}
     */
    public function readExtendedMetadata($meta, array &$config)
    {
        /**
         * @var \SimpleXmlElement
         */
        $xml = $this->_getMapping($meta->name);
        $xmlDoctrine = $xml;
        $xml = $xml->children(self::GEDMO_NAMESPACE_URI);

        if (in_array($xmlDoctrine->getName(), ['mapped-superclass', 'entity', 'document', 'embedded-document'])) {
            if (isset($xml->{'soft-deleteable'})) {
                $field = $this->_getAttribute($xml->{'soft-deleteable'}, 'field-name');

                if (!$field) {
                    throw new InvalidMappingException('Field name for SoftDeleteable class is mandatory.');
                }

                Validator::validateField($meta, $field);

                $config['softDeleteable'] = true;
                $config['fieldName'] = $field;
            }
        }
    }
}
