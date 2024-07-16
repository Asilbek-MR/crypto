<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin-page-services" class="admin-page">

    <div class="admin-page-header ui inverted blue segment">
        <h1 class="ui inverted header">
            <div class="content">
                Services Page
                <div class="sub header">
                    Service listing provided by <a class="ui teal label" target="_blank" href="https://btcinhere.com/">BTC In Here</a>
                </div>
            </div>
        </h1>
        <div class="ui green labeled icon button" ng-click="save()">
            <i class="save icon"></i>
            Save
        </div>
        <a href="{{ urls.services_page }}" target="_blank" class="ui labeled icon button">
            <i class="external square alternative icon"></i>
            Preview
        </a>
    </div>

    <div class="admin-page-content">

        <div class="field">
            <div class="ui toggle checkbox">
                <input type="checkbox" ng-model="settings.enabled">
                <label>Enabled</label>
            </div>
        </div>

        <div ng-if="settings.enabled">

            <div class="ui raised segment" >
                <div class="ui header">Header</div>

                <div class="field">
                    <label>Top HTML</label>
                    <textarea placeholder="<div>place here HTML</div>" rows="5" ng-model="settings.header_top_html"></textarea>
                </div>

                <div class="field">
                    <label>Title</label>
                    <admin-multi-lang-input content="settings.title"></admin-multi-lang-input>
                </div>

                <div class="field">
                    <label>Subtitle</label>
                    <admin-multi-lang-input content="settings.subtitle"></admin-multi-lang-input>
                </div>

                <div class="field">
                    <label>Bottom HTML</label>
                    <textarea placeholder="<div>place here HTML</div>" rows="5" ng-model="settings.header_bottom_html"></textarea>
                </div>
            </div>

            <div class="ui raised segment">
                <div class="ui header">Options</div>

                <div class="inline fields">
                    <div class="field">
                        <label>Items per row:</label>
                        <select class="ui dropdown" ng-model="settings.items_per_row">
                            <option value="one">One</option>
                            <option value="two">Two</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Items per page:</label>
                        <input type="number" placeholder="25" ng-model="settings.page_size">
                    </div>
                </div>
            </div>

            <div class="ui raised segment">
                <div class="ui header">Services</div>

                <div class="field">
                    <label>Blocked</label>
                    <admin-search values="services_list" multiple="true" model="settings.blocked_list"></admin-search>
                </div>

                <div class="ui divider"></div>

                <div class="field">
                    <div class="two fields">
                        <div class="six wide field">
                            <label>Service</label>
                            <admin-search values="services_list" model="selected_service"></admin-search>
                        </div>
                        <div class="ten wide field">
                            <label>Overridden URL</label>
                            <input type="url" placeholder="https://some_url.domain" ng-model="settings.overridden_urls[selected_service]">
                    </div>
                    </div>
                </div>


                <!--
                <div class="ui tiny blue labeled icon button" ng-click="addOverridenItem()">
                    <i class="plus icon"></i>
                    Add
                </div>

                <h4 class="ui header">List</h4>

                <div class="ui selection large list">
                    <div class="item" ng-repeat="(slug,url) in settings.overridden_urls">
                        <div class="right floated content">
                            <div class="ui tiny basic icon button" ng-click="removeOverridenItem(slug)">
                                <i class="remove icon"></i>
                            </div>
                        </div>
                        <div class="content">
                            <div class="header">{{ slug }}</div>
                            <div class="description">{{ url }}</div>
                        </div>
                    </div>
                </div>
                -->
            </div>

            <div class="ui raised segment">
                <admin-custom-services services="settings.custom_services"></admin-custom-services>
            </div>

            <div class="ui raised segment">
                <div class="ui header">After</div>
                <div class="field">
                    <label>Custom HTML</label>
                    <textarea placeholder="<div>place here HTML</div>" rows="5" ng-model="settings.after_html"></textarea>
                </div>
            </div>

            <admin-custom-seo settings="settings"></admin-custom-seo>

        </div>



    </div>

</div>