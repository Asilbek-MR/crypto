'use strict';

(function (CONSTANTS, $, angular, moment) {
    var isDefined = angular.isDefined,
        isObject = angular.isObject,
        isNumber = angular.isNumber,
        isUndefined = angular.isUndefined,
        isArray = angular.isArray,
        isFunction = angular.isFunction,
        isString = angular.isString,
        element = angular.element,
        noop = angular.noop,
        fromJson = angular.fromJson,
        copy = angular.copy,
        merge = angular.merge,
        isDate = angular.isDate,
        padNumber = function (num, size) {var s = num+'';while (s.length < size) s = '0' + s;return s;},
        inArray = function(array,obj){return isArray(array) ? array.indexOf(obj) !== -1 : false;},
        isPlainObject = function (obj) {return Object.prototype.toString.call(obj) === '[object Object]'};

    CONSTANTS.loadedMS = Date.now()

    /****************************************************************************
     *                                                                          *
     *                          Sidebar Menu Handler                            *
     *                                                                          *
     ****************************************************************************/
    /**
     * Scree Watcher
     * keeps tracking screen width for menu display
     *
     * @param elements
     * @constructor
     */

    function ScreenWatcher(elements) {
        var self = this;
        self.screen_size = null;
        self.elements = elements;
    }

    /**
     * Takes action if screen changes
     */
    ScreenWatcher.prototype.handler = function () {
        var size = this.screen_size,
            elements = this.elements;

        if(size === 's') { // small screen
            elements.menu
                .hide();
            elements.main
                .show()
                .attr('class','sixteen wide column');
            elements.mobile_bar
                .show();
        }
        else if(size === 'm') { // medium screen
            elements.menu
                .show()
                .attr('class','three wide column');
            elements.main
                .show()
                .attr('class','thirteen wide column');
            elements.mobile_bar
                .hide();
        }
        else if(size === 'l') { // large screen
            elements.menu
                .show()
                .attr('class','two wide column');
            elements.main
                .show()
                .attr('class','fourteen wide column');
            elements.mobile_bar
                .hide();
        }
    };

    /**
     * Checks screen width changes
     *
     * @returns {boolean}
     */
    ScreenWatcher.prototype.check = function () {
        var w = this.elements.window.width();
        var current_screen;

        if(w >= 1920) {
            current_screen = 'l';
        }
        else if(w >= 1024) {
            current_screen = 'm';
        }
        else {
            current_screen = 's';
        }

        if(current_screen !== this.screen_size) {
            this.screen_size = current_screen;
            return true;
        }
        else return false;
    };

    /**
     * Sets resize watchdog
     */
    ScreenWatcher.prototype.watch = function () {
        var self = this;
        self.elements.window.resize(function() {
            self.check() && self.handler();
        });
    };

    /**
     * Initializes
     */
    ScreenWatcher.prototype.init = function () {
        this.check() && this.handler();
        this.watch();
    };

    var closeMobileMenu = noop;

    $(function () {

        // collect html elements
        var elements = {
            window: $( window ),
            menu: $('#admin-menu'),
            main: $('#admin-main'),
            mobile_bar: $('#admin-mobile-bar'),
            main_form: $('#admin-main-form'),
            mobile_menu: $('#admin-mobile-menu')
        };

        // disable form submit, all actions are async
        elements.main_form
            .unbind('submit');

        // instantiate the screen watcher
        var sw = new ScreenWatcher(elements);
        sw.init();

        elements.mobile_menu
            .sidebar();

        // show/hide sidebar menu on click event
        elements.mobile_bar
            .find('.menu-opener').click(function () {
                elements.mobile_menu
                    .sidebar('toggle');
            });

        closeMobileMenu = function () {
            elements.mobile_menu
                .sidebar('hide');
        };
    });

    /****************************************************************************
     *                                                                          *
     *                                  App                                     *
     *                                                                          *
     ****************************************************************************/

    var module = angular.module('AdminApp', ['ui.router'])
        
        .config(['$stateProvider','$urlRouterProvider','$compileProvider',
            function ($stateProvider,$urlRouterProvider,$compileProvider) {
                // default state
                $urlRouterProvider.otherwise('/general');

                // admin templates URL
                function templateURL(state) {
                    return CONSTANTS.urls.admin+'template/'+state+'?_='+CONSTANTS.loadedMS;
                }

                /**
                 *  UI Router states (https://ui-router.github.io/ng1/docs/latest/)
                 */

                // simple states

                [
                    'general',
                    'social',
                    'header_menu',
                    'press_page',
                    'donation',
                    'footer',
                    'market_page',
                    'mining_page',
                    'converter_page',
                    'icos_page',
                    'currency_page',
                    'mining_script',
                    'exchanges_page',
                    'services_page',
                    'trends_page'
                ].forEach(function(state){
                    var resolve = {},
                        settings = state + '_settings';

                    resolve[settings] = ['API',
                        function (API) {
                            return API.options.get(settings)
                        }
                    ];

                    $stateProvider
                        .state(state, {
                            url: '/'+state,
                            templateUrl: templateURL(state),
                            controller: state,
                            resolve: resolve
                        });
                });

                // more complex states

                $stateProvider
                    .state('coins', {
                        url: '/coins',
                        templateUrl: templateURL('coins'),
                        controller: 'coins'
                    });

                $stateProvider
                    .state('coin', {
                        url: '/coin/{slug}',
                        templateUrl: templateURL('coin'),
                        controller: 'coin',
                        resolve: {
                            coin: ['API','$stateParams',
                                function (API,$stateParams) {
                                    return API.coins.info($stateParams.slug);
                                }
                            ]
                        }
                    });

                $stateProvider
                    .state('custom_pages', {
                        url: '/custom_pages',
                        templateUrl: templateURL('custom_pages'),
                        controller: 'custom_pages',
                        resolve: {
                            pages: ['API',
                                function (API) {
                                    return API.custom_pages.getAll();
                                }
                            ]
                        }
                    });

                $stateProvider
                    .state('custom_page', {
                        url: '/custom_page/{id}',
                        templateUrl: templateURL('custom_page'),
                        controller: 'custom_page'
                    });

                $stateProvider
                    .state('users', {
                        url: '/users',
                        templateUrl: templateURL('users'),
                        controller: 'users',
                        resolve: {
                            users: ['API',
                                function (API) {
                                    return API.users.getAll();
                                }
                            ]
                        }
                    });

                $stateProvider
                    .state('user', {
                        url: '/user/{id}',
                        templateUrl: templateURL('user'),
                        controller: 'user'
                    });

                $stateProvider
                    .state('custom_asset', {
                        url: '/custom_asset/{id}',
                        templateUrl: templateURL('custom_asset'),
                        controller: 'custom_asset'
                    });

                $compileProvider.debugInfoEnabled(false);
            }
        ])

        .run(['$rootScope','image_gallery','API','$interval','$transitions','page_loader',
            function ($rootScope,image_gallery,API,$interval,$transitions,page_loader) {

                // inject to rootScope constants
                [
                    'urls',
                    'social_networks',
                    'twitter_cards',
                    'themes',
                    'timezones',
                    'languages',
                    'date_formats',
                    'time_formats',
                    'market_columns',
                    'web_miners'
                ].forEach(function (c) {
                    $rootScope[c] = CONSTANTS[c];
                });

                $rootScope.image_gallery = image_gallery;

                // check if user stills logged in every 5 minutes
                $interval(function () {
                    API.session()
                        .then(function (logged) {
                            if(!logged){
                                window.location.reload();
                            }
                        },function () {
                            window.location.reload();
                        })
                },5*60000);

                $transitions.onStart({}, function () {
                    page_loader.start();
                });

                $transitions.onSuccess({}, function() {
                    closeMobileMenu();

                    page_loader.end();
                    $('html, body').animate({scrollTop: 0}, 400);
                });
            }
        ])
    ;

    /****************************************************************************
     *                                                                          *
     *                               Services                                   *
     *                                                                          *
     ****************************************************************************/

    module

        .service('API',['$http','$q',
            function ($http,$q) {
                /**
                 * All required API endpoints
                 */

                var API = this;

                API._url = CONSTANTS.urls.api;

                API.endpointURL = function() {
                    var path = [].slice.call(arguments).join('/');
                    var url = new URL(API._url+path)
                    url.searchParams.set('_', Date.now())
                    return url.toString();
                };

                // request return an promise
                // if response status is 200 will throw success otherwise fail
                API.httpRequest = function (config) {
                    return $q(function (resolve, reject) {

                        function sucess(response) {
                            resolve(response.data); // just provide response data
                        }

                        function error(response) {
                            var data = response.data;

                            // error data will always be an object

                            if(isArray(data)){
                                var errors = {};
                                data.forEach(function (prop) { errors[prop] = true; });
                                return reject(errors);
                            }
                            else if(isPlainObject(data)) {
                                return reject(data);
                            }

                            reject({});
                        }

                        $http(config)
                            .then(sucess,error);
                    });
                };

                // check if user is logged
                API.session = function () {
                    return API.httpRequest({
                        method: 'GET',
                        url: API.endpointURL('session')
                    });
                };

                // system options endpoint
                API.options = {
                    get: function (name) {
                        return API.httpRequest({
                            method: 'GET',
                            url: API.endpointURL('options',name)
                        });
                    },
                    save: function (name,obj) {
                        return API.httpRequest({
                            method: 'POST',
                            url: API.endpointURL('options',name),
                            data: obj
                        });
                    }
                };

                // get all (built-in & custom) pages
                API.pages = function () {
                    return API.httpRequest({
                        method: 'GET',
                        url: API.endpointURL('pages')
                    });
                };

                // custom pages CRUD
                API.custom_pages = {
                    getAll: function () {
                        return API.httpRequest({
                            method: 'GET',
                            url: API.endpointURL('pages','custom')
                        });
                    },
                    get: function (id) {
                        return API.httpRequest({
                            method: 'GET',
                            url: API.endpointURL('pages','custom',id)
                        });
                    },
                    create: function (obj) {
                        return API.httpRequest({
                            method: 'POST',
                            url: API.endpointURL('pages','custom'),
                            data: obj
                        });
                    },
                    update: function (id,obj) {
                        return API.httpRequest({
                            method: 'POST',
                            url: API.endpointURL('pages','custom',id),
                            data: obj
                        });
                    },
                    remove: function (id) {
                        return API.httpRequest({
                            method: 'DELETE',
                            url: API.endpointURL('pages','custom',id)
                        });
                    }
                };

                // price rates
                API.rates = {
                    all: function () {
                        return API.httpRequest({
                            method: 'GET',
                            url: API.endpointURL('rates')
                        });
                    },
                    list: function () {
                        return API.httpRequest({
                            method: 'GET',
                            url: API.endpointURL('rates','list')
                        });
                    }
                };


                // cryptocurrencies
                API.coins = {
                    list: function () {
                        return API.httpRequest({
                            method: 'GET',
                            url: API.endpointURL('coins','list')
                        });
                    },
                    info: function (slug) {
                        return API.httpRequest({
                            method: 'GET',
                            url: API.endpointURL('coins','info',slug)
                        });
                    },
                    update: function (slug, data) {
                        return API.httpRequest({
                            method: 'POST',
                            data: data,
                            url: API.endpointURL('coins','update',slug)
                        });
                    },
                    updateInfo: function (slug) {
                        return API.httpRequest({
                            method: 'POST',
                            url: API.endpointURL('coins','update_info',slug)
                        });
                    },
                    updateStatus: function (slug, status) {
                        return API.httpRequest({
                            method: 'POST',
                            url: API.endpointURL('coins','update_status',slug, status)
                        });
                    }
                };

                // images
                API.images = {
                    list: function () {
                        return API.httpRequest({
                            method: 'GET',
                            url: API.endpointURL('images')
                        });
                    },
                    remove: function (name) {
                        return API.httpRequest({
                            method: 'DELETE',
                            url: API.endpointURL('images',name)
                        });
                    },
                    upload: function (data) {
                        return API.httpRequest({
                            method: 'POST',
                            headers: {'Content-Type': undefined},
                            url: API.endpointURL('images'),
                            data: data
                        });
                    }
                };

                // users CRUD
                API.users = {
                    getAll: function () {
                        return API.httpRequest({
                            method: 'GET',
                            url: API.endpointURL('users')
                        });
                    },
                    get: function (id) {
                        return API.httpRequest({
                            method: 'GET',
                            url: API.endpointURL('users',id)
                        });
                    },
                    create: function (obj) {
                        return API.httpRequest({
                            method: 'POST',
                            url: API.endpointURL('users'),
                            data: obj
                        });
                    },
                    update: function (id,obj) {
                        return API.httpRequest({
                            method: 'POST',
                            url: API.endpointURL('users',id),
                            data: obj
                        });
                    },
                    remove: function (id) {
                        return API.httpRequest({
                            method: 'DELETE',
                            url: API.endpointURL('users',id)
                        });
                    }
                };

                // custom assets CRUD
                API.custom_assets = {
                    getAll: function () {
                        return API.httpRequest({
                            method: 'GET',
                            url: API.endpointURL('custom_assets')
                        });
                    },
                    get: function (id) {
                        return API.httpRequest({
                            method: 'GET',
                            url: API.endpointURL('custom_assets',id)
                        });
                    },
                    create: function (obj) {
                        return API.httpRequest({
                            method: 'POST',
                            url: API.endpointURL('custom_assets'),
                            data: obj
                        });
                    },
                    update: function (id,obj) {
                        return API.httpRequest({
                            method: 'POST',
                            url: API.endpointURL('custom_assets',id),
                            data: obj
                        });
                    },
                    remove: function (id) {
                        return API.httpRequest({
                            method: 'DELETE',
                            url: API.endpointURL('custom_assets',id)
                        });
                    }
                };

                API.services = {
                    list: function () {
                        return API.httpRequest({
                            method: 'GET',
                            url: API.endpointURL('services')
                        });
                    }
                };
            }
        ])

        .service('page_loader', [
            function () {
                var loader = element('#admin-main-loading'); // loader element
                var events = []; // current events list

                // create a loading event
                this.start = function (event) {
                    // push events if provided
                    if(isArray(event)) {
                        events = events.concat(event);
                    }
                    else if(isString(event)) {
                        events.push(event);
                    }

                    loader.addClass('loading'); // starts loading effect
                };

                // close a loading event
                this.end = function (event) {
                    if(event) { // removes event from list
                        var found = events.indexOf(event);
                        if(found !== -1) {
                            events.splice(found,1);
                        }
                    }

                    if(!events.length) {
                        loader.removeClass('loading'); // ends loading effect
                    }
                };
            }
        ])

        .service('image_gallery', ['API','$parse',
            function (API,$parse) {
                var ig = this,
                    modal = element('#admin-image-gallery'),
                    form = modal.find('#admin-image-gallery-form'),
                    input = form.find('input');

                ig.images = [];

                ig.target = {
                    context: null,
                    path: null
                };

                ig.open = function (context, path) {
                    API.images.list()
                        .then(function (images) {
                            ig.images = images;

                            modal.modal('show');

                            ig.target.context = context;
                            ig.target.path = path;
                        });
                };

                ig.select = function (index) {
                    var image = ig.images[index],
                        target = ig.target;

                    if(target.context && target.path) {
                        $parse(target.path).assign(target.context, image.url);
                    }

                    modal.modal('hide');
                };

                ig.upload = function () {
                    input.off('change');
                    input.one('change', function(event){
                        var tgt = event.target || window.event.srcElement, files = tgt.files;
                        if (FileReader && files && files.length) {
                            var fr = new FileReader();

                            if(files[0].size > 1024*1024) {
                                alert('Max Size (1MB) Exceeded');
                            }
                            else {
                                API.images.upload(new FormData(form[0]))
                                    .then(function () {
                                        API.images.list()
                                            .then(function (images) {ig.images = images;})

                                    })
                            }
                        }
                        else console.log('File Reader Not Supported');
                    });

                    input.click();
                };

                ig.remove = function (index) {
                    if(!confirm('Are you sure your want to remove this image? This operation is irreversible.'))
                        return;

                    var image = ig.images[index];

                    API.images.remove(image.name)
                        .then(function (result) {
                            if(result) {
                                ig.images.splice(index,1);
                            }
                        })
                };


            }
        ])

        .service('languages', ['API',
            function (API) {
                var self = this;

                self.list = CONSTANTS.languages;
                self.current = 'en';

                API.options.get('general_settings')
                    .then(function (general) {
                        self.current = general.language;
                    });
            }
        ])

        .factory('translationContent', [
            function () {
                return function (content, lang) {
                    var text = null;

                    if(!isPlainObject(content)) return text;

                    if(lang) text = content[lang];

                    if(!text) text = content.en;

                    if(!text) {
                        Object.keys(content).some(function (l) {
                            var t = content[l];
                            if(t) {
                                text = t;
                                return true;
                            }
                        });
                    }

                    return text;
                };
            }
        ])

        .component('adminImage', {
            bindings: {
                image: '=',
                title: '@'
            },

            template: [
                '<div class="two fields"><div class="field">',
                    '<label>{{ $ctrl.title }}</label>',
                    '<img ng-click="$ctrl.open()" class="ui bordered small image" ng-src="{{ $ctrl.image || $ctrl.placeholder }}">',
                    '<br>',
                    '<div class="ui action input">',
                        '<input type="url" ng-model="$ctrl.image">',
                        '<div class="ui teal right labeled icon button" ng-click="$ctrl.open()">',
                            '<i class="image icon"></i>Select',
                        '</div>',
                    '</div>',
                '</div></div>'
            ].join(''),

            controller: ['image_gallery',
                function (image_gallery) {
                    var ctrl = this;

                    ctrl.placeholder = CONSTANTS.image_placeholder;

                    ctrl.open = function () {
                        image_gallery.open(ctrl, 'image');
                    };

                    ctrl.$onInit = function () {

                    };
                }
            ]
        })

        .component('adminMenu', {
            bindings: {
                items: '='
            },
            template: [
                '<div>',
                    '<h4 class="ui header">New Item</h4>',
                    '<div class="fields">',
                        '<div class="four wide field" ng-class="{error: $ctrl.warning.type}">',
                            '<label>Type</label>',
                            '<select class="ui dropdown" ng-model="$ctrl.new_item.type" ng-options="type as details[0] for (type,details) in $ctrl.types"></select>',
                        '</div>',
                        '<div class="five wide field" ng-if="$ctrl.show(\'url\')" ng-class="{error: $ctrl.warning.url}">',
                            '<label>URL</label>',
                            '<input type="url" ng-model="$ctrl.new_item.data">',
                        '</div>',
                        '<div class="seven wide field" ng-if="$ctrl.show(\'text\')" ng-class="{error: $ctrl.warning.text}">',
                            '<label>Text</label>',
                            '<admin-multi-lang-input content="$ctrl.new_item.text">',
                        '</div>',
                        '<div class="four wide field" ng-if="$ctrl.show(\'page\')" ng-class="{error: $ctrl.warning.page}">',
                            '<label>Page</label>',
                            '<select class="ui dropdown" ng-model="$ctrl.new_item.data" ng-options="id as title for (id,title) in $ctrl.pages"></select>',
                        '</div>',
                        '<div class="four wide field" ng-if="$ctrl.show(\'custom_page\')" ng-class="{error: $ctrl.warning.custom_page}">',
                            '<label>Custom Page</label>',
                            '<select class="ui dropdown" ng-model="$ctrl.new_item.data" ng-options="id as title for (id,title) in $ctrl.custom_pages"></select>',
                        '</div>',
                        '<div class="four wide field" ng-if="$ctrl.show(\'social\')" ng-class="{error: $ctrl.warning.social}">',
                            '<label>Social Network</label>',
                            '<select class="ui dropdown" ng-model="$ctrl.new_item.data" ng-options="id as details[1] for (id,details) in $ctrl.social_networks"></select>',
                        '</div>',
                    '</div>',


                    '<div class="ui tiny blue labeled icon button" ng-click="$ctrl.add()"><i class="plus icon"></i>Add</div>',
                    '<h4 class="ui header">Items</h4>',

                    '<div class="ui selection large list" ng-if="$ctrl.items.length">',
                        '<div class="item" ng-repeat="item in $ctrl.items">',
                            '<div class="right floated content">',
                                '<div class="tiny ui basic icon buttons">',
                                    '<div class="ui button" ng-if="!$first" ng-click="$ctrl.up($index)"><i class="up chevron icon"></i></div>',
                                    '<div class="ui button" ng-if="!$last" ng-click="$ctrl.down($index)"><i class="down chevron icon"></i></div>',
                                    '<div class="ui button" ng-click="$ctrl.edit($index)"><i class="pencil alternative icon"></i></div>',
                                    '<div class="ui button" ng-click="$ctrl.remove($index)"><i class="trash icon"></i></div></div>',
                            '</div>',
                            '<i class="{{ $ctrl.types[item.type][1] }} icon"></i>',
                            '<div class="content">',
                                '<div class="header">{{ $ctrl.getTitle(item) }}</div> ',
                                '<div class="description">{{ $ctrl.getContent(item) }}</div> ',
                            '</div>',
                        '</div>',
                    '</div>',
                    '<div class="ui message" ng-if="!$ctrl.items.length"><div class="content">No Items Found</div></div>',
                '</div>'
            ].join(''),
            controller: ['$element','API','$scope','translationContent',
                function ($element,API,$scope,translationContent) {
                    var ctrl = this;

                    // types of menu items
                    ctrl.types = {
                        link: ['Link', 'external link'],
                        page: ['Page', 'file'],
                        custom_page: ['Custom page', 'file outline'],
                        donation: ['Donation','hand peace'],
                        social: ['Social', 'thumbs up']
                    };
                    ctrl.new_item = {};
                    ctrl.warning = {};
                    ctrl.custom_pages = {};
                    ctrl.pages = {};
                    ctrl.social_networks = CONSTANTS.social_networks;
                    ctrl.default_lang = 'en';

                    // control field display
                    ctrl.show = function (field) {
                        var type = ctrl.new_item.type;

                        if(field === 'text') return type === 'link';
                        if(field === 'url') return type === 'link';
                        if(field === 'page') return type === 'page';
                        if(field === 'custom_page') return type === 'custom_page';
                        if(field === 'social') return type === 'social';

                        return false;
                    };

                    // adds new item to menu
                    ctrl.add = function () {
                        var new_item = ctrl.new_item,
                            type = new_item.type,
                            data = new_item.data,
                            text = translationContent(new_item.text, ctrl.default_lang);

                        // error warning
                        ctrl.warning = {};

                        // check all fields
                        switch (type) {
                            case 'link':
                                if(!data || !text) {
                                    if(!data) ctrl.warning.url = true;
                                    if(!text) ctrl.warning.text = true;
                                    return;
                                }
                                break;
                            case 'page':
                                if(!ctrl.pages[data]) {
                                    if(!ctrl.pages[data]) ctrl.warning.page = true;
                                    return;
                                }
                                break;
                            case 'custom_page':
                                if(!ctrl.custom_pages[data]) {
                                    if(!ctrl.custom_pages[data]) ctrl.warning.custom_page = true;
                                    if(!text) ctrl.warning.text = true;
                                    return;
                                }
                                break;
                            case 'donation':
                                break;
                            case 'social':
                                if(!ctrl.social_networks[data]) {
                                    ctrl.warning.social = true;
                                    return;
                                }
                                break;
                            default:
                                ctrl.warning.type = true;
                                return;
                        }

                        // adds items
                        ctrl.items.push(ctrl.new_item);
                        ctrl.new_item = {};
                    };

                    // item listing representative title
                    ctrl.getTitle = function (item) {
                        var type = item.type,
                            data = item.data;

                        if(type === 'social') {
                            var network = ctrl.social_networks[item.data];
                            return network[1] || '(Not A Social Network)';
                        }
                        if(type === 'custom_page') {
                            return ctrl.custom_pages[data] || 'Not visible or nonexistent ?!';
                        }
                        else if(type === 'page') {
                            return ctrl.pages[data] || 'Not visible or nonexistent ?!';
                        }
                        else if(type === 'donation') {
                            return 'Donation';
                        }
                        else {
                            return translationContent(item.text, ctrl.default_lang);
                        }
                    };

                    // item listing representative content
                    ctrl.getContent = function (item) {
                        var content = '',
                            type = item.type,
                            data = item.data;

                        if(type === 'link') {
                            content = data;
                        }

                        return content;
                    };

                    ctrl.edit = function (index) {
                        ctrl.new_item = ctrl.items[index];
                        ctrl.items.splice(index,1);
                    };

                    // remove item
                    ctrl.remove = function (index) {
                        ctrl.items.splice(index,1);
                    };

                    // move item up
                    ctrl.up = function (index) {
                        var items = ctrl.items;

                        if(index > 0){
                            var item = items[index];
                            items[index] = items[index-1];
                            items[index-1] = item;
                        }
                    };

                    // move item down
                    ctrl.down = function (index) {
                        var items = ctrl.items;

                        if(index < items.length - 1){
                            var item = items[index];
                            items[index] = items[index+1];
                            items[index+1] = item;
                        }
                    };

                    // request all pages
                    API.pages().then(function (pages) {

                        pages.built_in.forEach(function (page) {
                            ctrl.pages[page.id] = page.title;
                        });

                        pages.custom.forEach(function (page) {
                            ctrl.custom_pages[page.id] = page.title;
                        });

                    });

                    API.options.get('general_settings')
                        .then(function (option) {
                            ctrl.default_lang = option.language;
                        });

                    // menu will always be an array
                    ctrl.$onInit = function () {
                        if(!isArray(ctrl.items)) {
                            ctrl.items = [];
                        }
                    };

                    // clear warnings on type change
                    $scope.$watch('$ctrl.new_item.type',function () {
                        ctrl.warning = {};
                    });

                }
            ]
        })

        .component('adminSearch', {
            bindings: {
                multiple: '@',
                values: '<',
                model: '='
            },
            template: [
                '<div class="ui fluid search selection dropdown">',
                    '<div class="text"></div>',
                    '<i class="dropdown icon"></i>',
                '</div>'
            ].join(''),
            controller: ['$element','$timeout',
                function ($element,$timeout) {
                    var ctrl = this;

                    // on change event
                    // links model to selected value
                    function onChange(value) {
                        if(!value) return;

                        // timeout ensures digest cycle
                        $timeout(function(){
                            if (ctrl.multiple) { // if multiple model expand value to an array
                                ctrl.model = value.split(',');
                            }
                            else if (value !== ctrl.model) { // replace model with new value
                                ctrl.model = value;
                            }
                        })
                    }

                    function init() {
                        // values must be an array
                        if (!isArray(ctrl.values))
                            return;

                        var dropdown = $element.find('.ui.search.dropdown'), // dropdown element
                            // settings for Semantic UI dropdown (http://semantic-ui.com/modules/dropdown.html)
                            settings = {
                                onChange: onChange,
                                values: JSON.parse(JSON.stringify(ctrl.values)),
                                forceSelection: false
                            };

                        if (ctrl.multiple === 'true') {

                            // model must be an array
                            if (!isArray(ctrl.model)) {
                                ctrl.model = [];
                            }

                            // pre-select model original value
                            settings.values.forEach(function (item) {
                                if (ctrl.model.indexOf(item.value) !== -1) {
                                    item.selected = true;
                                }
                            });

                            dropdown.addClass('multiple');
                        }
                        else {

                            settings.values.some(function (item) {
                                if (ctrl.model === item.value) {
                                    item.selected = true;
                                    return true;
                                }
                            });
                        }

                        dropdown.dropdown(settings); // dropdown initialization
                    }

                    ctrl.$onInit = init; // on ready to init

                    // init if values change
                    ctrl.$onChanges = function (changes) {
                        changes.values && init();
                    }
                }
            ]
        })

        .component('adminCustomServices', {
            bindings: {
                services: '='
            },
            template: [
                '<div>',
                    '<div class="ui header">Custom Services</div><div class="field"><div class="ui tiny blue labeled icon button" ng-click="$ctrl.add()"><i class="plus icon"></i>New Service</div></div>',
                    '<div class="ui fluid styled accordion" ng-show="$ctrl.services.length">',
                        '<div class="title" ng-repeat-start="service in $ctrl.services"><i class="dropdown icon"></i>{{ service.name }}</div>',
                        '<div class="content" ng-repeat-end>',
                            '<div class="two fields">',
                                '<div class="six wide field"><label>Name</label><input type="text" placeholder="My Service" ng-model="service.name"></div>',
                                '<div class="ten wide field"><label>URL</label><input type="url" placeholder="https://some_url.domain" ng-model="service.url"></div>',
                            '</div>',
                            '<div class="field"><label>Description</label><textarea rows="3" placeholder="Describe the service" ng-model="service.description"></textarea></div>',
                            '<div class="field"><label>Tags (separated by commas)</label><input type="text" placeholder="payments, software" ng-model="service.tags"></div>',


                            '<div class="ui red button" ng-click="$ctrl.remove($index)">Remove</div>',
                        '</div>',
                    '</div>',
                    '<div class="ui message" ng-show="!$ctrl.services.length"><div class="content">No services were found.</div></div>',
                '</div>'
            ].join(''),

            controller: ['$element',
                function ($element) {
                    var ctrl = this;


                    ctrl.add = function() {
                        ctrl.services.push({
                            name: 'New Service',
                            url: null,
                            description: null,
                            tags: null
                        })
                    };

                    ctrl.remove = function(index) {
                        if(confirm('Are you sure?')) {
                            ctrl.services.splice(index,1);
                        }
                    };


                    ctrl.$onInit = function () {
                        if(!isArray(ctrl.services)) {
                            ctrl.services = [];
                        }

                        $element.find('.ui.accordion').accordion();
                    }
                }
            ]
        })

        .component('adminDateRange', {
            bindings: {
                start: '=',
                end: '='
            },
            template: [
                '<div class="two fields">',
                    '<div class="field"><label>Start Date</label><div class="ui calendar start-date"><div class="ui input left icon"><i class="calendar icon"></i><input type="text" placeholder="Start"></div></div></div>',
                    '<div class="field"><label>End Date</label><div class="ui calendar end-date"><div class="ui input left icon"><i class="calendar icon"></i><input type="text" placeholder="End"></div></div></div>',
                '</div>'
            ].join(''),
            controller: ['$element','$timeout',
                function ($element,$timeout) {
                    var ctrl = this;



                    ctrl.$onInit = function () {

                        var start = $element.find('.start-date'),
                            end = $element.find('.end-date');

                        start.calendar({
                            endCalendar: end,
                            ampm: false,
                            onChange: function (date) {
                                $timeout(function () {
                                    ctrl.start = date;
                                });
                            }
                        });

                        end.calendar({
                            startCalendar: start,
                            ampm: false,
                            onChange: function (date) {
                                $timeout(function () {
                                    ctrl.end = date;
                                });
                            }
                        });

                        if(ctrl.start) {
                            if(!isDate(ctrl.start)) ctrl.start = new Date(ctrl.start);

                            start.calendar('set date', ctrl.start, true, false);
                        }

                        if(ctrl.end) {
                            if(!isDate(ctrl.end)) ctrl.end = new Date(ctrl.end);

                            end.calendar('set date', ctrl.end, true, false);
                        }

                    }
                }
            ]


        })

        .component('adminCustomIcos', {
            bindings: {
                icos: '='
            },
            template: [
                '<div>',
                    '<div class="field"><div class="ui tiny blue labeled icon button" ng-click="$ctrl.add()"><i class="plus icon"></i>New ICO</div></div>',
                    '<div class="ui fluid styled accordion">',
                        '<div class="title" ng-repeat-start="ico in $ctrl.icos"><i class="dropdown icon"></i>{{ ico.name }}</div>',
                        '<div class="content" ng-repeat-end>',
                            '<div class="two fields">',
                                '<div class="six wide field"><label>Name</label><input type="text" placeholder="My Service" ng-model="ico.name"></div>',
                                '<div class="ten wide field"><label>Website</label><input type="url" placeholder="https://some_url.domain" ng-model="ico.website"></div>',
                            '</div>',
                            '<div class="field"><label>Description</label><textarea rows="3" placeholder="Describe The ICO" ng-model="ico.description"></textarea></div>',

                            '<admin-date-range start="ico.start_date" end="ico.end_date"></admin-date-range>',

                            '<div class="field">',
                                '<div class="ui toggle checkbox">',
                                    '<input type="checkbox" ng-model="ico.featured">',
                                    '<label>Featured</label>',
                                '</div>',
                            '</div>',

                            '<admin-image image="ico.image" title="Image URL"></admin-image>',


                            '<div class="ui red button" ng-click="$ctrl.remove($index)">Remove</div>',
                        '</div>',
                    '</div>',
                '</div>'
            ].join(''),
            controller: ['$element','image_gallery',
                function ($element,image_gallery) {
                    var ctrl = this;

                    ctrl.image_gallery = image_gallery;

                    ctrl.add = function() {
                        ctrl.icos.push({
                            name: 'New ICO',
                            website: null,
                            image: null,
                            description: null,
                            start_date: null,
                            end_date: null,
                            featured: false
                        })


                    };

                    ctrl.remove = function(index) {
                        if(confirm('Are you sure?')) {
                            ctrl.icos.splice(index,1);
                        }
                    };


                    ctrl.$onInit = function () {
                        if(!isArray(ctrl.icos)) {
                            ctrl.icos = [];
                        }

                        $element.find('.ui.accordion').accordion();
                    }
                }
            ]
        })

        .component('adminMultiLangTextarea', {
            bindings: {
                content: '=',
                rows: '@'
            },

            template: [
                '<div class="inline field">',
                    '<select class="ui dropdown" ng-model="$ctrl.languages.current" ng-options="lang.value as $ctrl.name(lang) for lang in $ctrl.languages.list"></select>',
                '</div>',
                '<div class="field">',
                    '<textarea rows="{{$ctrl.rows}}" ng-model="$ctrl.content[$ctrl.languages.current]"></textarea>',
                '</div>'
            ].join(''),

            controller: ['languages',
                function (languages) {
                    var ctrl = this;

                    ctrl.$onInit = function () {
                        if(!isPlainObject(ctrl.content)) ctrl.content = {};

                        ctrl.languages = languages;

                        ctrl.name = function (lang) {
                            if(!ctrl.content) return;

                            var c = ctrl.content[lang.value];
                            return (!!c && c.length ? lang.name + ' ●' : lang.name);
                        };
                    }
                }
            ]

        })

        .component('adminMultiLangInput', {
            bindings: {
                content: '=',
            },

            template: [
                '<div class="ui left action input">',
                    '<select class="ui dropdown" ng-model="$ctrl.languages.current" ng-options="lang.value as $ctrl.name(lang) for lang in $ctrl.languages.list"></select>',
                    '<input type="text" ng-model="$ctrl.content[$ctrl.languages.current]">',
                '</div>',
            ].join(''),

            controller: ['languages',
                function (languages) {
                    var ctrl = this;

                    ctrl.$onInit = function () {
                        if(!isPlainObject(ctrl.content)) ctrl.content = {};

                        ctrl.languages = languages;

                        ctrl.name = function (lang) {
                            if(!ctrl.content) return;

                            var c = ctrl.content[lang.value];
                            return (!!c && c.length ? lang.name + ' ●' : lang.name);
                        };
                    }
                }
            ]

        })

        .component('adminCustomSeo', {
            bindings: {
                settings: '='
            },

            template: [
                '<div class="ui raised segment">',
                    '<div class="ui header">Custom SEO</div>',
                    '<div class="field"><div class="ui toggle checkbox"><input type="checkbox" ng-model="$ctrl.settings.seo_enabled"><label>Enabled</label></div></div>',
                    '<div ng-if="$ctrl.settings.seo_enabled">',
                        '<div class="field"><label>Title</label><input type="text" placeholder="SEO Title" ng-model="$ctrl.settings.seo_title"></div>',
                        '<div class="field"><label>Description</label><textarea rows="2" placeholder="SEO Description" ng-model="$ctrl.settings.seo_description"></textarea></div>',
                        '<admin-image image="$ctrl.settings.seo_og_image_url" title="OpenGraph Image URL"></admin-image>',
                        '<admin-image image="$ctrl.settings.seo_twitter_image_url" title="Twitter Image URL"></admin-image>',
                    '</div>',
                '</div>'
            ].join('')
        })
    ;

    /****************************************************************************
     *                                                                          *
     *                             Controllers                                  *
     *                                                                          *
     ****************************************************************************/

    /**
     * Standard controller builder (simple states)
      */
    function controllerBuilder(name, extend) {
        var settings_name = name + '_settings';

        module.controller(name, [settings_name,'$scope','$element','API','page_loader','$state','$stateParams',
            function (settings,$scope,$element,API,page_loader,$state,$stateParams) {
                $scope.errors = {};
                $scope.settings = settings; // add option content to scope

                $scope.save = function () { // save button handler
                    page_loader.start();

                    API.options.save(settings_name, $scope.settings)
                        .then(function (option) {
                            $scope.settings = option; // refresh with feedback data
                            page_loader.end();
                        },function (errors) {
                            $scope.errors = errors;
                            page_loader.end();
                        });
                };

                // extend controller execution
                if(extend) {
                    extend.apply(this, arguments);
                }

            }
        ])
    }

    controllerBuilder('general', function (general_settings,$scope,$element,API) {
        $scope.pages = null;
        $scope.rates = null;

        // get all pages for front page
        API.pages().then(function (pages) {
            $scope.pages = pages;
        });

        // get rates for default price currency
        API.rates.list().then(function (rates) {
            $scope.rates = rates;
        });
    });
    controllerBuilder('social');
    controllerBuilder('header_menu');
    controllerBuilder('press_page', function (press_page_settings,$scope) {
        $scope.new_feed = {url: ''};

        // adds feed if valid
        // it's using input type url to ensure a valid entry
        // validation may not work on old browsers
        $scope.addFeed = function () {
            var url = $scope.new_feed.url;
            if(url) {
                press_page_settings.feeds.push(url);
                $scope.new_feed.url = '';
            }
        };

        // removes feed from list
        $scope.removeFeed = function (index) {
            press_page_settings.feeds.splice(index,1);
        }
    });
    controllerBuilder('donation', function (donation_settings, $scope) {

        $scope.new_address = {
            name: '',
            address: ''
        };
        // adds item to list
        // address and name must be filled
        $scope.addAddress = function () {
            var n = $scope.new_address;
            if(n.name && n.address) {
                donation_settings.addresses.push(n);
                $scope.new_address = {};
            }
        };

        // removes address from list
        $scope.removeAddress = function (index) {
            donation_settings.addresses.splice(index,1);
        }
    });
    controllerBuilder('footer');
    controllerBuilder('front_page');
    controllerBuilder('market_page');
    controllerBuilder('mining_page');
    controllerBuilder('converter_page');
    controllerBuilder('icos_page');
    controllerBuilder('currency_page');
    controllerBuilder('mining_script');
    controllerBuilder('exchanges_page');
    controllerBuilder('services_page', function (services_page_settings,$scope,$element,API) {
        $scope.services_list = null;
        $scope.selected_service = null;

        if(!isPlainObject(services_page_settings.overridden_urls)) {
            services_page_settings.overridden_urls = {};
        }


        API.services.list()
            .then(function (list) {
                $scope.services_list = list.map(function (service) {
                    return {
                        value: service.slug,
                        name: service.name
                    }
                });
            })


    });
    controllerBuilder('trends_page');

    module

        .controller('custom_pages', ['pages','$scope','API','$state','page_loader','translationContent',
        function (pages,$scope,API,$state,page_loader,translationContent) {
            $scope.pages = pages;

            // get custom page url
            $scope.getURL = function (index) {
                var page = $scope.pages[index],
                    identifier = page.path || page.id; // use path (if defined)

                return CONSTANTS.urls.custom_pages+identifier+'?preview=1'; // override visibility for preview
            };

            // redirects to custom page state
            $scope.edit = function (index) {
                var page = $scope.pages[index];
                $state.go('custom_page', {id: page.id});
            };

            // remove custom page
            $scope.remove = function (index) {
                var page = $scope.pages[index];

                if(!confirm('Do you want to remove '+page.title+'?')) // alert user
                    return;

                page_loader.start();

                function end() {
                    page_loader.end();
                }

                API.custom_pages.remove(page.id)
                    .then(function (removed) {
                        if(removed)
                            $scope.pages.splice(index,1); // on success remove from list

                        end();
                    },end)
            };

            $scope.getName = function (index) {
                var page = $scope.pages[index];

                return translationContent(page.title);
            };

        }
    ])

        .controller('custom_page', ['$scope','$stateParams','$state','API','page_loader',
        function ($scope,$stateParams,$state,API,page_loader) {

            $scope.errors = {};

            var id = $stateParams.id;

            if(!id) {
                $state.go('custom_pages');
            }
            else if(id === 'new') { // if creating, empty object
                $scope.page = {};
            }
            else { // get custom page data
                page_loader.start();

                API.custom_pages.get(id)
                    .then(function (page) {
                        $scope.page = page;
                        page_loader.end();
                    },function (errors) {
                        page_loader.end();
                    })
            }

            // save custom page
            $scope.save = function () {
                page_loader.start();
                var page = $scope.page;

                $scope.errors = {};

                if(page.id) { // update request
                    API.custom_pages.update(page.id, page)
                        .then(function (page) {
                            $scope.page = page; // refresh object will feedback data
                            page_loader.end();
                        },function (errors) {
                            $scope.errors = errors; // show errors
                            page_loader.end();
                        });
                }
                else { // create request
                    API.custom_pages.create(page)
                        .then(function (page) {
                            page_loader.end();
                            $state.go('custom_page', {id: page.id}) // if created, refresh state with new id
                        },function (errors) {
                            $scope.errors = errors; // show errors
                            page_loader.end();
                        })
                }
            };

            // custom page url
            $scope.getURL = function () {
                var page = $scope.page,
                    identifier = page.path || page.id; // use path (if defined) or id

                return CONSTANTS.urls.custom_pages+identifier+'?preview=1' // override visibility for preview
            };

            // remove custom page
            $scope.remove = function () {
                var page = $scope.page;

                if(!confirm('Do you want to remove '+page.title+'?')) // alert user
                    return;

                API.custom_pages.remove(page.id)
                    .then(function () {
                        $state.go('custom_pages'); // redirect to custom pages list state
                    })
            }
        }
    ])

        .controller('users',['users','$scope','page_loader','$state','API',
            function (users,$scope,page_loader,$state,API) {
                $scope.users = users;

                // redirect to user state with id
                $scope.edit = function (index) {
                    var user = $scope.users[index];
                    $state.go('user', {id: user.id});
                };

                // remove user
                $scope.remove = function (index) {
                    var user = $scope.users[index];

                    if(!confirm('Do you want to remove '+user.email+'?')) // alert user
                        return;

                    page_loader.start();

                    function end() {
                        page_loader.end();
                    }

                    API.users.remove(user.id)
                        .then(function (removed) {
                            if(removed)
                                $scope.users.splice(index,1); // on success, remove from list

                            end();
                        },end)
                };

                // redirect to user state for creation (with id = new)
                $scope.create = function () {
                    $state.go('user', {id: 'new'});
                };
            }
        ])

        .controller('user', ['$scope','$stateParams','$state','API','page_loader',
        function ($scope,$stateParams,$state,API,page_loader) {
            var id = $stateParams.id;

            $scope.passwords = ['',''];
            $scope.updated = false;

            if(!id) {
                $state.go('users');
            }
            else if(id === 'new') { // if creation, empty object
                $scope.user = {
                    active: 1
                };
            }
            else { // get user details
                page_loader.start();

                API.users.get(id)
                    .then(function (user) {
                        $scope.user = user;
                        page_loader.end();
                    },function () {
                        page_loader.end();
                    })
            }

            // validates passwords
            // passwords should have length between 8 and 20 by default
            function passwordValidation() {
                var passwords = $scope.passwords,
                    invalid = !passwords[0] || passwords[0].length < 8 || passwords[0].length > 20 || passwords[0] !== passwords[1];

                if(invalid) { // on invalid combination
                    passwords[0] = ''; // reset passwords
                    passwords[1] = '';
                    alert('Please correct your password');
                    return false;
                }

                $scope.user.password = passwords[0]; // if good, pass to user object
                return true;
            }

            // save user object
            $scope.save = function () {
                var user = $scope.user,
                    passwords = $scope.passwords;

                if(user.id) { // update action
                    if((passwords[0] || passwords[1]) && !passwordValidation()) // validate password, if changing
                        return;

                    page_loader.start();

                    API.users.update(user.id, user)
                        .then(function (updated) {
                            page_loader.end();
                            $state.go('user', {id: user.id}, {reload: true}); // on success, reload state for data refresh
                        },function () { // on failure, reset passwords
                            passwords[0] = '';
                            passwords[1] = '';
                            page_loader.end();
                        });
                }
                else { // create action
                    if(!passwordValidation())
                        return;

                    page_loader.start();

                    API.users.create(user)
                        .then(function (id) {
                            page_loader.end();

                            if(id) {
                                $state.go('user', {id: id}) // refresh state with new id
                            }

                        },function () { // on failure, reset passwords
                            passwords[0] = '';
                            passwords[1] = '';
                            page_loader.end();
                        })
                }
            };

            // remove user
            $scope.remove = function () {
                var user = $scope.user;

                if(!confirm('Do you want to remove '+user.email+'?')) // alert user
                    return;

                API.users.remove(user.id)
                    .then(function (removed) {
                        if(removed)
                            $state.go('users'); // redirect to users list state
                    })
            }

        }
    ])

        .controller('custom_asset', ['$scope','$stateParams','$state','API','page_loader',
            function ($scope,$stateParams,$state,API,page_loader) {

                $scope.errors = {};
                $scope.coins = null;
                $scope.page_url = null;

                var id = $stateParams.id;

                // get all cryptocurrencies unfiltered
                // call must wait for custom asset object (admin-search element model)
                function loadAllCryptos() {
                    API.coins.list()
                        .then(function (coins) {
                            $scope.coins = coins;
                        });
                }

                if(!id) {
                    return $state.go('coins');
                }
                else if(id === 'new') { // if creation, empty object
                    $scope.asset = {};
                    loadAllCryptos(); // load cryptos
                }
                else { // get custom asset data
                    page_loader.start();

                    API.custom_assets.get(id)
                        .then(function (asset) {
                            $scope.asset = asset;
                            $scope.page_url = CONSTANTS.urls.currency_page + asset.slug;
                            loadAllCryptos(); // load cryptos
                            page_loader.end();
                        },function () {
                            page_loader.end();
                        })
                }

                // save custom asset object
                $scope.save = function () {
                    page_loader.start();
                    var asset = $scope.asset;

                    $scope.errors = {};

                    if(asset.id) { // update action
                        API.custom_assets.update(asset.id, asset)
                            .then(function (asset) {
                                $scope.asset = asset; // refresh object will feedback data
                                page_loader.end();
                            },function (errors) {
                                $scope.errors = errors; // show errors
                                page_loader.end();
                            });
                    }
                    else { // create action
                        API.custom_assets.create(asset)
                            .then(function (asset) {
                                page_loader.end();
                                $state.go('custom_asset', {id: asset.id}) // refresh state with new id
                            },function (errors) {
                                $scope.errors = errors; // show errors
                                page_loader.end();
                            })
                    }
                };

                // remove custom asset
                $scope.remove = function () {
                    var asset = $scope.asset;

                    if(!confirm('Do you want to remove '+asset.name+'?')) // alert user
                        return;

                    API.custom_assets.remove(asset.id)
                        .then(function () {
                            $state.go('coins'); // redirect to cryptocurrency state
                        })
                };
            }
        ])

        .controller('coins', ['$scope','API','$element','$compile',
            function ($scope,API,$element,$compile) {
                $scope.custom_assets = [];

                var table = $element.find('table'),
                    datatable = table.DataTable({
                        scrollX: true,
                        ajax: CONSTANTS.urls.api + 'coins/table',
                        columns: [
                            {
                                data: 'name',
                                render: function (data,type,row) {
                                    return "<a ui-sref=\"coin({slug:'"+row.slug+"'})\">"+data+"</a>";
                                }
                            },
                            {
                                data: 'symbol'
                            },
                            {
                                data: 'info_updated',
                                render: function (data,type,row) {
                                    var date = data ? moment.unix(data).format('YYYY/MM/DD HH:mm') : 'Never',
                                        button = "&nbsp;<div ng-click=\"updateInfo('"+row.slug+"')\" class=\"ui right floated mini icon teal button\"><i class=\"sync icon\"></i></div>";

                                    return date + button;
                                }
                            },
                            {
                                data: 'prices_updated',
                                render: function (data) {
                                    return data ? moment.unix(data).format('YYYY/MM/DD HH:mm') : 'Never';
                                }
                            },
                            {
                                data: 'status',
                                render: function (data,type,row) {
                                    var color, text, status;

                                    if(data === 1) {
                                        color = 'green';
                                        text = 'enabled';
                                        status = 0;
                                    } else {
                                        color = 'red';
                                        text = 'disabled';
                                        status = 1;
                                    }

                                    return "<div ng-click=\"updateStatus('"+row.slug+"',"+status+")\" style=\"cursor: pointer\" class=\"ui center aligned fluid "+color+" label\">"+text+"</div>";
                                }
                            }
                        ],
                        rowCallback: function (row, data) {
                            $compile(row)($scope);
                        }
                });

                $scope.updateAll = function () {
                    datatable.ajax.reload(null, false);
                };

                $scope.updateInfo = function (slug) {
                    API.coins.updateInfo(slug)
                        .then(function () {
                            datatable.ajax.reload(null, false);
                        });
                };

                $scope.updateStatus = function(slug,status) {
                    API.coins.updateStatus(slug,status)
                        .then(function () {
                            datatable.ajax.reload(null, false);
                        });
                };

                API.custom_assets.getAll()
                    .then(function (custom_assets) {
                        $scope.custom_assets = custom_assets;
                    });

            }
        ])

        .controller('coin', ['coin','$scope','page_loader','API','$element',
            function (coin,$scope,page_loader,API,$element) {
                var slug = coin.slug;

                $scope.page_url = CONSTANTS.urls.currency_page + slug;

                function prepare(obj) {
                    obj.info_updated = moment.unix(obj.info_updated).format('YYYY/MM/DD HH:mm');
                    obj.prices_updated = moment.unix(obj.prices_updated).format('YYYY/MM/DD HH:mm');

                    return obj;
                }

                $scope.coin = prepare(coin);

                $scope.updateInfo = function () {
                    API.coins.updateInfo(slug).then(function (info_updated) {
                        coin.info_updated = moment.unix(info_updated).format('YYYY/MM/DD HH:mm');
                    })

                };

                $scope.save = function () {

                    page_loader.start();

                    API.coins.update(slug, {
                        status: $scope.coin.status,
                        page_content: $scope.coin.page_content
                    }).then(function (coin) {
                        $scope.coin = prepare(coin);
                        page_loader.end();
                    },function () {
                        page_loader.end();
                    })


                };
            }
        ])


    ;


})(window.CoinTableAdminConstants,jQuery,angular,moment);