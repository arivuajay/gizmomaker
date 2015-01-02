<?php
/**
 * Created by PhpStorm.
 * User: asanka
 * Date: 11/10/14
 * Time: 3:46 AM
 */

namespace Gizmo\GizmoBundle\Service;


use Lsw\ApiCallerBundle\Call\HttpGetJson;
use Lsw\ApiCallerBundle\Caller\ApiCallerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class ContentManager {

    /**
     * @var ApiCallerInterface
     */
    private $apiCaller;

    /**
     * @var String
     */
    private $cmsApiBaseUrl;

    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(EngineInterface $templating, ApiCallerInterface $apiCaller, $cms_api_url){
        $this->apiCaller = $apiCaller;
        $this->cmsApiBaseUrl = $cms_api_url;
        $this->templating  = $templating;
    }


    public function replaceCustomTags($currentContent, $category = null, $title = null,$section = 'articles'){

        $url = $this->cmsApiBaseUrl;
        $parameters = [];

        if($category){
            $url .= urlencode($category);
        }else{
            $url .= 'index';
        }
        if($title)
            $url .= '/'.urlencode($title);

        $url .= '?from_domain='. 'http://gizmomaker.co.il/new/'.$section.'/&';

        $tags_reg_exp = '^(?:\{right_menu\})|(?:\{top_menu\})|(?:\{cat_promote_[0-9]+\})|(?:\{categories\})|(?:\{box_none_fixed\})|(?:\{box_fixed\})|(?:\{title\})|(?:\{head\})|(?:\{name\})|(?:\{summary\})|(?:\{content\})|(?:\{(?:in|out)_(?:random|latest|related)_[0-9]+\})|(?:\{cat[0-9]+\}+)|(?:\{blog\})|(?:\{meta\})^';

        preg_replace_callback($tags_reg_exp,function($matches) use(&$url){
            $url .= urlencode($matches[0]).'='.'1&';
            return $matches[0];
        }, $currentContent);

        $result = (array) $this->apiCaller->call(new HttpGetJson($url,$parameters));


        $currentContent = preg_replace_callback($tags_reg_exp, function($matches) use($result){

            if(!empty($result[$matches[0]])){
                return $result[$matches[0]];
            }
            return '';
        }, $currentContent);


        return $currentContent;
    }


    public function replaceRandomProjectTags($currentContent,$randomProjects,$homeSliderProjects = null){

        $renderedContent = $this->templating->render( 'GizmoBundle:Partials:random_projects.html.twig',['randomProjects'=>$randomProjects]);

        $renderedContent2 = $this->templating->render('GizmoBundle:Partials:home_projects_slide.html.twig',['randomProjects'=>$homeSliderProjects]);
        $currentContent = str_replace('{random_projects}',$renderedContent,$currentContent);
        if(!empty($homeSliderProjects))
        $currentContent = str_replace('{home_projects_slide}',$renderedContent2,$currentContent);

        return $currentContent;
    }

} 