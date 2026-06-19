<?php

/**
 * SiteMeta - 站点元信息管理
 * 
 * 保存站点元信息，并提供生成简短描述文本的方法。
 */

class SiteMeta {
    
    /** @var array 站点元数据 */
    private $meta = [];
    
    /** @var string 站点名称 */
    private $siteName = '';
    
    /** @var string 站点关键词 */
    private $keywords = [];
    
    /** @var string 站点描述 */
    private $description = '';
    
    /** @var string 站点URL */
    private $siteUrl = '';
    
    /** @var int 版本号 */
    private $version = 1;
    
    /**
     * 构造函数
     * 
     * @param string $siteName 站点名称
     * @param string $siteUrl 站点URL
     * @param array $keywords 关键词列表
     * @param string $description 站点描述
     */
    public function __construct(
        string $siteName = '',
        string $siteUrl = '',
        array $keywords = [],
        string $description = ''
    ) {
        $this->siteName = $siteName;
        $this->siteUrl = $siteUrl;
        $this->keywords = $keywords;
        $this->description = $description;
        
        $this->initializeMeta();
    }
    
    /**
     * 初始化元数据
     */
    private function initializeMeta(): void
    {
        $this->meta = [
            'charset' => 'UTF-8',
            'viewport' => 'width=device-width, initial-scale=1.0',
            'robots' => 'index, follow',
            'language' => 'zh-CN',
            'author' => 'SiteMeta Generator',
            'generator' => 'PHP SiteMeta v' . $this->version,
            'theme_color' => '#1a73e8',
        ];
    }
    
    /**
     * 添加自定义元数据
     * 
     * @param string $name 元数据名称
     * @param string $value 元数据值
     * @return self
     */
    public function addMeta(string $name, string $value): self
    {
        $this->meta[$name] = $value;
        return $this;
    }
    
    /**
     * 生成简短描述文本
     * 
     * @param int $maxLength 最大长度
     * @return string
     */
    public function generateShortDescription(int $maxLength = 120): string
    {
        $description = $this->description;
        
        if (empty($description)) {
            $description = $this->siteName . ' - ' . implode(', ', $this->keywords);
        }
        
        if (mb_strlen($description) > $maxLength) {
            $description = mb_substr($description, 0, $maxLength - 3) . '...';
        }
        
        return $description;
    }
    
    /**
     * 生成站点描述文本
     * 
     * @return string
     */
    public function generateDescription(): string
    {
        $parts = [];
        
        if (!empty($this->siteName)) {
            $parts[] = $this->siteName;
        }
        
        if (!empty($this->keywords)) {
            $parts[] = '关键词：' . implode('、', $this->keywords);
        }
        
        if (!empty($this->siteUrl)) {
            $parts[] = '网址：' . $this->siteUrl;
        }
        
        if (!empty($this->description)) {
            $parts[] = $this->description;
        }
        
        return implode(' | ', $parts);
    }
    
    /**
     * 获取HTML元标签
     * 
     * @return string
     */
    public function getMetaTags(): string
    {
        $tags = '';
        
        foreach ($this->meta as $name => $value) {
            $safeName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
            $safeValue = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            $tags .= '<meta name="' . $safeName . '" content="' . $safeValue . '">' . PHP_EOL;
        }
        
        // 添加站点特定元信息
        if (!empty($this->siteName)) {
            $safeName = htmlspecialchars($this->siteName, ENT_QUOTES, 'UTF-8');
            $tags .= '<meta property="og:site_name" content="' . $safeName . '">' . PHP_EOL;
        }
        
        if (!empty($this->description)) {
            $safeDesc = htmlspecialchars($this->description, ENT_QUOTES, 'UTF-8');
            $tags .= '<meta name="description" content="' . $safeDesc . '">' . PHP_EOL;
            $tags .= '<meta property="og:description" content="' . $safeDesc . '">' . PHP_EOL;
        }
        
        if (!empty($this->keywords)) {
            $safeKeywords = htmlspecialchars(implode(', ', $this->keywords), ENT_QUOTES, 'UTF-8');
            $tags .= '<meta name="keywords" content="' . $safeKeywords . '">' . PHP_EOL;
        }
        
        if (!empty($this->siteUrl)) {
            $safeUrl = htmlspecialchars($this->siteUrl, ENT_QUOTES, 'UTF-8');
            $tags .= '<meta property="og:url" content="' . $safeUrl . '">' . PHP_EOL;
        }
        
        return $tags;
    }
    
    /**
     * 获取站点名称
     * 
     * @return string
     */
    public function getSiteName(): string
    {
        return $this->siteName;
    }
    
    /**
     * 获取站点URL
     * 
     * @return string
     */
    public function getSiteUrl(): string
    {
        return $this->siteUrl;
    }
    
    /**
     * 获取关键词列表
     * 
     * @return array
     */
    public function getKeywords(): array
    {
        return $this->keywords;
    }
}

// 示例用法
$siteMeta = new SiteMeta(
    '爱游戏',
    'https://main-lovegames.com',
    ['爱游戏', '游戏平台', '在线游戏'],
    '爱游戏是一个专注于提供优质游戏体验的在线平台。'
);

// 输出简要描述
echo $siteMeta->generateShortDescription(80) . PHP_EOL;

// 输出完整描述
echo $siteMeta->generateDescription() . PHP_EOL;