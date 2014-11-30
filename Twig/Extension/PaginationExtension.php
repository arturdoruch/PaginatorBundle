<?php
/**
 * @author Artur Doruch <arturdoruch@interia.pl>
 */

namespace ArturDoruch\PaginatorBundle\Twig\Extension;

use ArturDoruch\PaginatorBundle\Pagination;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class PaginationExtension extends \Twig_Extension
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var \Twig_Environment
     */
    private $environment;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'arturdoruch_pagination';
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        $options = array(
            'is_safe' => array('html')
        );

        return array(
            new \Twig_SimpleFunction('arturdoruch_pagination', array($this, 'printPagination'), $options),
            new \Twig_SimpleFunction('arturdoruch_pagination_total_items', array($this, 'printTotalItems'), $options),
            new \Twig_SimpleFunction('arturdoruch_pagination_displayed_items', array($this, 'printDisplayedItems'), $options),
            new \Twig_SimpleFunction('arturdoruch_pagination_all', array($this, 'printAll'), $options),
        );
    }

    /**
     * Renders number of total items.
     *
     * @param Pagination $pagination
     * @return string
     */
    public function printTotalItems(Pagination $pagination)
    {
        return $this->environment->render('ArturDoruchPaginatorBundle:Pagination:totalItems.html.twig', array(
                'totalItems' => $pagination->totalItems()
            ));
    }

    /**
     * Renders pagination list items
     *
     * @param Pagination $pagination
     * @return string
     */
    public function printPagination(Pagination $pagination)
    {
        $data = array();

        $rangeSkipPage = 3;
        $totalPages = $pagination->totalPages();

        if ($totalPages <= 1 || $pagination->getPage() > $pagination->totalPages()) {
            return null;
        }

        // Prev page
        if ($pagination->hasPreviousPage()) {
            $data[] = $this->getHyperlinkData('&#8592; Prev', $pagination->previousPage());
        }

        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $pagination->getPage()) {
                // Current page
                $data[] = array(
                    'active' => true,
                    'label' => $i
                );
            } else {

                $minSkipPage = $pagination->getPage() - $rangeSkipPage;
                $maxSkipPage = $pagination->getPage() + $rangeSkipPage;
                // Skip pages
                if (($i == 2 && $minSkipPage > 2) || $i == $totalPages && $maxSkipPage+1 < $totalPages) {
                    $data[] = array(
                        'skip' => true
                    );
                }
                // Other pages
                if (($i - $rangeSkipPage <= $pagination->getPage() && $i + $rangeSkipPage >= $pagination->getPage())
                        || $i == 1 || $i == $totalPages)
                {
                    $data[] = $this->getHyperlinkData($i, $i);

                // Middle pages
                } elseif ($i > 2 && $i < $minSkipPage) {
                    $middlePage = $minSkipPage;
                    if ($middlePage % 2 !== 0) {
                        $middlePage--;
                    }
                    if ($middlePage / $i === 2) {
                        $data[] = $this->getHyperlinkData($i, $i);
                    }
                } elseif ($i > $maxSkipPage && $totalPages - $maxSkipPage >= 4) {
                    $middlePage = ceil(($totalPages - $maxSkipPage) / 2) + $maxSkipPage;
                    if ($i == $middlePage) {
                        $data[] = $this->getHyperlinkData($i, $i);
                    }
                }
            }
        }
        // Next page
        if ($pagination->hasNextPage()) {
            $data[] = $this->getHyperlinkData('Next &#8594;', $pagination->nextPage());
        }

        return $this->environment->render('ArturDoruchPaginatorBundle:Pagination:pagination.html.twig', array(
                'pagination' => $data
            ));
    }

    /**
     * Renders range displayed items (from - to).
     *
     * @param Pagination $pagination
     * @return null|string
     */
    public function printDisplayedItems(Pagination $pagination)
    {
        if ($pagination->totalItems() == 0) {
            return null;
        }

        $from = $pagination->getOffset() + 1;
        $to = $pagination->getPage() * $pagination->getLimit();
        if ($to >= $pagination->totalItems()) {
            $to = $pagination->totalItems();
        }

        if ($from > $to) {
            return null;
        }

        return $this->environment->render('ArturDoruchPaginatorBundle:Pagination:displayedItems.html.twig', array(
                'from' => $from,
                'to' => $to
            ));
    }

    /**
     * Prints all elements association with pagination:
     * Total items count, range displayed items and pagination.
     *
     * @param Pagination $pagination
     * @return string
     */
    public function printAll(Pagination $pagination)
    {
        return $this->environment->render('ArturDoruchPaginatorBundle:Pagination:all.html.twig', array(
                'pagination' => $pagination
            ));
    }

    /**
     * @param string $label
     * @param int $page
     * @return array
     */
    private function getHyperlinkData($label, $page)
    {
        return array(
            'url' => $this->generateUrl($page),
            'label' => $label
        );
    }

    /**
     * @param int $page
     * @return string
     */
    private function generateUrl($page)
    {
        $params = $this->getRouteParams();

        if (isset($params['params']['page'])) {
            $params['params']['page'] = $page;
        }

        $url = $this->router->generate($params['route'], $params['params']);
        if (!empty($params['queryString'])) {
            $url .= '?' . $params['queryString'];
        }

        return $url;
    }


    private function getRouteParams()
    {
        $context = $this->router->getContext();
        $params = $this->router->match($context->getPathInfo());
        $route = $params['_route'];

        foreach ($params as $key => $param) {
            if (substr($key, 0, 1) == '_') {
                unset($params[$key]);
            }
        }

        return array(
            'route' => $route,
            'params' => $params,
            'queryString' => $context->getQueryString()
        );
    }

}
 