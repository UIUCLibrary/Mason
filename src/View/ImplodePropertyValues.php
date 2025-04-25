<?php
namespace Mason\View;

use \Laminas\View\Helper\AbstractHelper;
use Omeka\Api\Representation\AbstractResourceEntityRepresentation;

class ImplodePropertyValues extends AbstractHelper
{
    public function __invoke(AbstractResourceEntityRepresentation $resource, string $property)
    {
        $view = $this->getView();
        $filterLocale = (bool) $view->siteSetting('filter_locale_values');
        $lang = $view->lang();
        $valueLang = $filterLocale ? [$lang, ''] : null;

        $implodedValues = '';
        $counter = 0;
        $propertyValueCount = count($resource->value($property,['all'=>true]));
        if ($propertyValueCount > 0){
            foreach ($resource->value($property,['all'=>true]) as $value){
                $counter++;
                //if this is the last entry, add "and"
                if ($counter > 1 && $counter == $propertyValueCount){
                    $implodedValues.=" and ";
                }
                if($value->valueResource()){
                    //if it is a resource, get a link to the resource
                    $implodedValues .= $value->valueResource()->linkRaw($value->valueResource()->displayTitle(null, $valueLang));
                } else {
                    //otherwise, get the text
                    $implodedValues .= $value->asHTML();
                }

                //if it is a long list, add comma after all but last and penultimate
                if ($counter < $propertyValueCount-1) {
                    $implodedValues.=', ';
                }
            }
        } else {
            $implodedValues = 'None';
        }
        return $implodedValues;
    }

}