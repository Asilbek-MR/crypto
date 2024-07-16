<?php
defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin-page-converter" class="admin-page">
    <div class="admin-page-header ui inverted blue segment">
        <h1 class="ui inverted header">
            <div class="content">
                Converter Page
                <div class="sub header">
                    Universal currency conversion page
                </div>
            </div>
        </h1>
        <div class="ui green labeled icon button" ng-click="save()">
            <i class="save icon"></i>
            Save
        </div>
        <a ng-href="{{ urls.converter_page }}" target="_blank" class="ui labeled icon button">
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