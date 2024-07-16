<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin-page-custom-pages" class="admin-page">
    <div class="admin-page-header ui inverted blue segment">
        <h1 class="ui inverted header">
            <div class="content">
                Custom Pages
                <div class="sub header">
                    Create your own pages
                </div>
            </div>
        </h1>

        <div class="ui teal labeled icon button" ui-sref="custom_page({id: 'new'})">
            <i class="add icon"></i>
            Create
        </div>
    </div>
    <div class="admin-page-content">

        <div class="ui raised segment">
            <div class="ui header">
                All Pages
            </div>
            <div class="ui selection very relaxed middle aligned divided large list" ng-if="pages.length">
                <div class="item" ng-repeat="page in pages">
                    <div class="right floated content">
                        <div class="ui basic icon buttons">
                            <a target="_blank" class="ui button" ng-href="{{ getURL($index) }}">
                                <i class="external icon"></i>
                            </a>
                            <div class="ui button" ui-sref="custom_page({id: page.id})">
                                <i class="pencil alternative icon"></i>
                            </div>
                            <div class="ui button" ng-click="remove($index)">
                                <i class="trash icon"></i>
                            </div>
                        </div>
                    </div>
                    <div class="content">
                        <h3 class="ui header">
                            <i ng-class="page.public ? 'eye icon' : 'eye slash icon'"></i>
                            <div class="content">
                                {{ getName($index) }}
                                <div class="sub header">/{{ page.path ? page.path : page.id }}</div>
                            </div>
                        </h3>
                    </div>

                </div>
            </div>

            <div class="ui message" ng-if="!pages.length">
                <div class="content">
                    No custom pages where found.
                </div>
            </div>
        </div>



    </div>
</div>
