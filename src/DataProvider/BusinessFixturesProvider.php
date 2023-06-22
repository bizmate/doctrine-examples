<?php

namespace App\DataProvider;

use App\Denormalizer\ReviewDenormalizer;
use App\Entity\Business;
use Symfony\Component\PropertyInfo\Extractor\ConstructorExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class BusinessFixturesProvider
{
    use JsonObjectFixtureLoader;
    
    const BASE_FIXTURES_PATH = '/src/Fixtures/';
    
    const BUSINESS_SUFFIX = '_business.json';
    
    /**
    * @var \Symfony\Component\Serializer\Serializer
    */
    private Serializer $serializer;
    
    public function __construct(ReviewDenormalizer $reviewDenormalizer)
    {
        $phpDocExtractor = new PhpDocExtractor();
        $constructorExtractor = new ConstructorExtractor([
            $phpDocExtractor
        ]);
        $propertyInfoExtractor = new PropertyInfoExtractor([],[
            $phpDocExtractor,
            $constructorExtractor
        ]);
        $objectNormalizer = new ObjectNormalizer(
            new ClassMetadataFactory(
                new AnnotationLoader()
            ),
            null,
            null,
            $propertyInfoExtractor
            //new PhpDocExtractor()
        );
        $normalizers = [
            //new ArrayDenormalizer(),
            $reviewDenormalizer,
            $objectNormalizer,
            new JsonSerializableNormalizer()
        ];

        $this->serializer = new Serializer($normalizers, [new JsonDecode()]);
    }
    
    /**
     * @param string $businessId
     * @return mixed|void
     */
    public function getBusiness(string $businessId)
    {
        try {
            $businessJson = self::loadJsonString($businessId . self::BUSINESS_SUFFIX);
            
            $businessObj = $this->serializer->deserialize($businessJson, Business::class, 'json', [
                'allow_extra_attributes' => true,
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true
            ]);
    
            //var_dump($businessObj);die;
            //return json_decode($businessJson);
            return $businessObj;
        } catch (\Exception $e) {
            error_log('Exception ' . $e->getMessage());
            die('Exception ' . $e->getMessage() . " trace: " . $e->getTraceAsString());
        }
    }
}