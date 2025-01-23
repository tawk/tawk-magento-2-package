<?php

declare(strict_types=1);

namespace Tawk\Widget\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\StoreManagerInterface;
use Laminas\Uri\UriFactory;

use Tawk\Helpers\PathHelper;
use Tawk\Widget\Model\WidgetFactory;

class UpdateExcludeIncludeUrl implements DataPatchInterface
{
    /**
     * Tawk.to Widget Model instance
     *
     * @var WidgetFactory $_modelWidgetFactory
     */
    private $_modelWidgetFactory;

    /**
     * Store Manager instance
     *
     * @var StoreManagerInterface $_modelStoreManager
     */
    private $_modelStoreManager;

    /**
     * Module Data Setup Interface
     *
     * @var ModuleDataSetupInterface
     */
     private $_moduleDataSetup;

    /**
     * Constructor
     *
     * @param WidgetFactory $modelWidgetFactory Tawk.to Widget Model instance
     * @param StoreManagerInterface $modelStoreManager Store Manager instance
     * @param ModuleDataSetupInterface $moduleDataSetup Module Data Setup Interface
     */
    public function __construct(
        WidgetFactory $modelWidgetFactory,
        StoreManagerInterface $modelStoreManager,
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->_modelWidgetFactory = $modelWidgetFactory;
        $this->_modelStoreManager = $modelStoreManager;
        $this->_moduleDataSetup = $moduleDataSetup;
    }

    /**
     * Get aliases (previous names) for the patch.
     *
     * @return string[]
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Get array of patches that have to be executed prior to this.
     *
     * @return string[]
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * Add new records with wildcards that are derived from the existing patterns.
     */
    public function apply()
    {
        $this->_moduleDataSetup->getConnection()->startSetup();

        $collection = $this->_modelWidgetFactory->create()->getCollection();

        foreach ($collection as $item) {
            $storeId = $item->getStoreId();
            $storeHost = $this->getStoreHost($storeId);

            $excludePatternList = $this->addWildcardToPatternList($item->getExcludeUrl(), $storeHost);
            $includePatternList = $this->addWildcardToPatternList($item->getIncludeUrl(), $storeHost);

            $item->setExcludeUrl($excludePatternList);
            $item->setIncludeUrl($includePatternList);
            $item->save();
        }

        $this->_moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Retrieves store url host
     *
     * @param int $storeId Store Id
     * @return string Store Url Host
     */
    private function getStoreHost($storeId)
    {
        $storeHost = '';

        $storeUrl = $this->_modelStoreManager->getStore($storeId)->getBaseUrl();
        $parsedUrl = UriFactory::factory($storeUrl);

        if (!empty($parsedUrl->getHost())) {
            $storeHost = $parsedUrl->getHost();
        }

        if (!empty($parsedUrl->getPort())) {
            $storeHost .= ':' . $parsedUrl->getPort();
        }

        return $storeHost;
    }

    /**
     * Processes the pattern list and adds a wildcard at the end of the pattern.
     *
     * @param string $patternList Pattern list separated with comma.
     * @param string $storeHost Store Host.
     * @return string Pattern list with wildcards.
     */
    private function addWildcardToPatternList($patternList, $storeHost)
    {
        if (empty($patternList)) {
            return '';
        }
        $splittedPatternList = preg_split("/,/", (string)$patternList);
        $wildcard = PathHelper::get_wildcard();

        $newPatternList = [];
        $addedPatterns = [];

        foreach ($splittedPatternList as $pattern) {
            if (empty($pattern)) {
                continue;
            }

            $pattern = ltrim($pattern, PHP_EOL);
            $pattern = trim($pattern);

            if (strpos($pattern, 'http://') !== 0 &&
                strpos($pattern, 'https://') !== 0 &&
                strpos($pattern, '/') !== 0
            ) {
                // Check if the first part of the string is a host.
                // If not, add a leading / so that the pattern
                // matcher treats is as a path.
                $firstPatternChunk = explode('/', $pattern)[0];
                if ($firstPatternChunk !== $storeHost) {
                    $pattern = '/' . $pattern;
                }
            }

            $newPatternList[] = $pattern;
            $newPattern = $pattern . '/' . $wildcard;
            if (in_array($newPattern, $splittedPatternList, true)) {
                continue;
            }

            if (true === isset($addedPatterns[$newPattern])) {
                continue;
            }

            $newPatternList[]             = $newPattern;
            $addedPatterns[$newPattern] = true;
        }

        // EOL for display purposes
        return join(',' . PHP_EOL, $newPatternList);
    }
}
