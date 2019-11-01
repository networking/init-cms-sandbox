networking init CMS configuration
=================================

This bundle has (for the time being) just a few configurable parameters which should be enough to
get you started.

This is an example of a possible CMS configuration:

```
networking_init_cms:
    languages:
        - {label: English, locale: en_US}
        - {label: Deutsch, locale: de_CH}
    templates:
        'sandbox_one_column':
            template: "ApplicationNetworkingInitCmsBundle:Default:one_column.html.twig"
            name: "Single Column"
            icon: "bundles/applicationnetworkinginitcms/img/template_header_one_column.png"
            controller: MyBundle::index
            zones:
                - { name: header, span:12 }
                - { name: main_content, span:12}
        'sandbox_two_column':
            template: "ApplicationNetworkingInitCmsBundle:Default:two_column.html.twig"
            name: "Two Column"
            icon: "bundles/applicationnetworkinginitcms/img/template_header_two_column.png"
            zones:
                - { name: header , span:12, max_content_items: 1, restricted_types: Networking\InitCmsBundle\Entity\Gallery}
                - { name: left , span:6}
                - { name: right , span:6}
    content_types:
        - { name: 'Text' , class: 'Networking\InitCmsBundle\Entity\Text'}
        - { name: 'Gallery' , class: 'Networking\InitCmsBundle\Entity\GalleryView'}
```


1) Configure the languages
--------------------------
The first parameter is an array of languages which configure the websites frontend language setup, each
language consists of two parameters:
    1. The label, used for display in the frontend
    2. The locale
    3. The short_label, which can be used in a language switch navigation for example

```
languages:
        - {label: English, locale: en_US, short_label: en}
        - {label: Deutsch, locale: de_CH, short_label: de}
```

2) Configure the frontend templates
-----------------------------------

The template configuration consists of an array where the array key is the name and location of the template
as you would write it in a twig template include.

The parameters fo the template include:
    1. "template": which template to render the page with
    2. "name": used for display in the backend
    3. "icon": a picture that should be no bigger than 175px wide. This will be shown in the backend to help
        the cms user visualise what the page could look like
    4. "controller": used to direct the request to the correct controller. default is NetworkingInitCmsBundle:FrontendPage:index
        if not set
    5. "zones": These are the areas in the template where content will be assigned to. Each zone has a "name" property
        (something sensible so the user can imagine what sort of content my go there), and "span" property which enables
        the CMS to roughly approximate the dimensions of the template. The span property is based in the twitter
        bootstrap grid system, where a span of 12 represents a css class of span12 ie the whole width of the page.

```
templates:
    'sandbox_one_column':
        template: "page/one_column.html.twig"
        name: "Single Column"
        icon: "build/img/template_header_one_column.png"
        controller: MyBundle::index # default NetworkingInitCmsBundle:FrontendPage:index
        zones:
            - { name: header, span:12, max_content_items: 1, restricted_types: Networking\InitCmsBundle\Entity\Gallery }
            - { name: main_content, span:12}
    'sandbox_two_column':
        template: "page/two_column.html.twig"
        name: "Two Column"
        icon: "build/img/template_header_two_column.png"
        zones:
            - { name: header , span:12}
            - { name: left , span:6}
            - { name: right , span:6}
```

Special attention should be made to the parameter "controller", this allows you to override the default action that a route
should be executed with. It is a good idea to extend the Networking\InitCmsBundle\Controller\FrontendController and use
the indexAction of the class so as to get all the right published or draft attributes. Then you can add your own functionality
afterwards e.g.

```
    use Networking\InitCmsBundle\Controller\FrontendController


    class DefaultController extends FrontendController
    {

            public function indexAction(Request $request)
            {
                $params = parent::indexAction($request);


                //do some extra stuff here, may be add some parameters etc

                return $params;
            }
    }
```

Also it is possible to restrict certain content zones to a specific content type using the "restricted_types" param
as well as limit the number of items allowed in a certain zone with the "max_content_items" parameter, the default is 0 which
means unlimited.

To learn more about templates see:
[Creating Templates](templates.md)

3) Configure the content types
------------------------------

This is where you can start to add you own content types as well as configure which ones of ours you would like to use.

Each content type consists of two properties:
    1. "name": once more a display name for the backend
    2. "class": the entity (including namespace) which will be used to contain the user input.

Content types can be simple entities consisting of just one field (such as the Text content type), or more of a
configuration entity, which configures an links to another entity (such as the Gallery content type), which has a
many-to-one relationship with a Networking\MediaBundle\Entity\Gallery entity (which actually contains the images).

```
content_types:
    - { name: 'Text' , class: 'Networking\InitCmsBundle\Entity\Text'}
    - { name: 'Gallery' , class: 'Networking\InitCmsBundle\Entity\GalleryView'}
```

To learn more about templates see:
[Creating content types](content_types.md)