<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin-page-custom-page" class="admin-page">
    <div class="admin-page-content">

        <div class="ui borderless mini labeled icon menu">
            <div class="active link item" ui-sref="custom_pages">
                <i class="reply icon"></i>
                Custom Pages
            </div>
            <a target="_blank" class="item" ng-href="{{ getURL() }}" ng-if="page.id">
                <i class="external icon"></i>
                Preview
            </a>
            <div class="link item" ng-click="save()">
                <i class="save icon"></i>
                Save
            </div>

            <div class="right menu">
                <div class="link item" ng-click="remove()" ng-if="page.id">
                    <i class="trash icon"></i>
                    Remove
                </div>
            </div>
        </div>


        <div class="ui raised segment">
            <div class="field">
                <div class="ui toggle checkbox">
                    <input type="checkbox" ng-model="page.public" ng-true-value="1" ng-false-value="0">
                    <label>Public</label>
                </div>
            </div>

            <div class="field">
                <label>Path</label>
                <div class="ui message">
                    <div class="content">
                        Use only alphanumeric & dash characters, eg: <strong>my-page-2018</strong>
                    </div>
                </div>
                <input type="text" placeholder="custom-path" ng-model="page.path">
            </div>

            <div class="field" ng-class="{error: errors.title}">
                <label>Title</label>
                <admin-multi-lang-input content="page.title"></admin-multi-lang-input>
            </div>

            <div class="field">
                <label>Subtitle</label>
                <admin-multi-lang-input content="page.subtitle"></admin-multi-lang-input>
            </div>

            <div class="field" ng-class="{error: errors.content}">
                <label>Content</label>
                <admin-multi-lang-textarea rows="6" content="page.content" rows="2"></admin-multi-lang-textarea>
            </div>
        </div>

        <admin-custom-seo settings="page"></admin-custom-seo>

    </div>
</div>