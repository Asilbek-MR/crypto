<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin-page-press" class="admin-page">
    <div class="admin-page-header ui inverted blue segment">
        <h1 class="ui inverted header">
            <div class="content">
                Press Page
                <div class="sub header">
                    RSS feeds page settings
                </div>
            </div>
        </h1>
        <div class="ui green labeled icon button" ng-click="save()">
            <i class="save icon"></i>
            Save
        </div>
        <a href="{{ urls.press_page }}" target="_blank" class="ui labeled icon button">
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
            <div class="ui raised segment">
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

                <div class="inline field">
                    <label>Item per page:</label>
                    <input type="number" placeholder="10" ng-model="settings.page_size">
                </div>

                <div class="ui message">
                    <div class="content">
                        Feeds will be fetched every 5 minutes.
                    </div>
                </div>
                <h4 class="ui header">New Feed</h4>
                <div class="ui action input">
                    <input type="url" placeholder="Feed URL" ng-model="new_feed.url">
                    <button class="ui blue right labeled icon button" ng-click="addFeed()">
                        <i class="plus icon"></i>
                        Add
                    </button>
                </div>
                <h4 class="ui header">Feeds List</h4>
                <div class="ui selection list" ng-if="settings.feeds.length > 0">
                    <div class="item" ng-repeat="feed in settings.feeds">
                        <div class="right floated content">
                            <div class="ui tiny icon basic button" ng-click="removeFeed($index)">
                                <i class="delete icon"></i>
                            </div>
                        </div>
                        <i class="feed icon"></i>
                        <div class="middle aligned content">
                            <div class="header">{{ feed }}</div>
                        </div>

                    </div>
                </div>
                <div class="ui message" ng-if="!settings.feeds.length">
                    <div class="content">
                        No feeds were found
                    </div>
                </div>
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