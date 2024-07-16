<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin-page-settings" class="admin-page">
    <div class="admin-page-header ui inverted blue segment">
        <h1 class="ui inverted header">
            <div class="content">
                General Settings
                <div class="sub header">
                    Website configuration options
                </div>
            </div>
        </h1>
        <div class="ui labeled icon green button" ng-click="save()">
            <i class="save icon"></i>
            Save
        </div>
    </div>
    <div class="admin-page-content">
        <div class="ui raised segment">

            <div class="two fields">
                <div class="field">
                    <label>Name</label>
                    <input type="text" placeholder="Website Name" ng-model="settings.name">
                </div>
            </div>

            <admin-image image="settings.logo_url" title="Logo URL"></admin-image>

            <admin-image image="settings.favicon_url" title="Favicon URL (PNG 16x16)"></admin-image>


            <div class="field">
                <label>Title</label>
                <admin-multi-lang-input content="settings.title"></admin-multi-lang-input>
            </div>



            <div class="field">
                <label>Description</label>
                <admin-multi-lang-textarea content="settings.description" rows="2"></admin-multi-lang-textarea>
            </div>
            <div class="two fields">
                <div class="field">
                    <label>Front Page</label>
                    <select class="ui dropdown" ng-model="settings.front_page">
                        <optgroup label="Built-In Pages">
                            <option ng-repeat="page in pages.built_in" ng-value="page.id">{{ page.title }}</option>
                        </optgroup>
                        <optgroup label="Custom Pages">
                            <option ng-repeat="page in pages.custom" ng-value="page.id">{{ page.title }}</option>
                        </optgroup>
                    </select>
                </div>
                <div class="field">
                    <label>Theme</label>
                    <select class="ui dropdown" ng-model="settings.layout_theme" ng-options="theme as details[0] for (theme, details) in themes"></select>
                </div>
            </div>
            <div class="two fields">
                <div class="field">
                    <label>Default Language</label>
                    <admin-search values="languages" model="settings.language"></admin-search>
                </div>
                <div class="field">
                    <label>Timezone</label>
                    <admin-search values="timezones" model="settings.timezone"></admin-search>
                </div>
            </div>

            <div class="two fields">
                <div class="field">
                    <label>Date Format</label>
                    <select class="ui dropdown" ng-model="settings.date_format" ng-options="format[0] as format[1] for format in date_formats"></select>
                </div>
                <div class="field">
                    <label>Time Format</label>
                    <select class="ui dropdown" ng-model="settings.time_format" ng-options="format[0] as format[1] for format in time_formats"></select>
                </div>
            </div>
            <div class="two fields">
                <div class="field">
                    <label>Default Price Currency</label>
                    <admin-search values="rates" model="settings.default_price"></admin-search>
                </div>
            </div>

        </div>

        <div class="ui raised segment">
            <div class="ui header">SEO</div>

            <admin-image image="settings.og_image_url" title="OpenGraph Image URL (Facebook, Reddit & others)"></admin-image>

            <div class="ui header">Twitter</div>

            <div class="three fields">
                <div class="field">
                    <label>Username (include @)</label>
                    <input type="text" placeholder="@username" ng-model="settings.twitter_username">
                </div>
                <div class="field">
                    <label>Creator (include @)</label>
                    <input type="text" placeholder="@creator" ng-model="settings.twitter_creator">
                </div>
                <div class="field">
                    <label>Card</label>
                    <select class="ui dropdown" ng-model="settings.twitter_card" ng-options="card as name for (card, name) in twitter_cards"></select>
                </div>
            </div>

            <admin-image image="settings.twitter_image_url" title="Image URL"></admin-image>
        </div>


        <div class="ui raised segment">
            <div class="ui header">GDPR</div>
            <p>
                We only use cookies for functional purposes, but external code used can set cookies too.</br>
                You should notify your users about this to fulfill <a target="_blank" href="https://ec.europa.eu/commission/priorities/justice-and-fundamental-rights/data-protection/2018-reform-eu-data-protection-rules_en">GDPR</a>.
            </p>

            <div class="field">
                <div class="ui toggle checkbox">
                    <input type="checkbox" ng-model="settings.gdpr_enabled">
                    <label>Enabled</label>
                </div>
            </div>

            <div ng-if="settings.gdpr_enabled">
                <div class="field">
                    <label>Title</label>
                    <admin-multi-lang-input content="settings.gdpr_title"></admin-multi-lang-input>
                </div>
                <div class="field">
                    <label>Message</label>
                    <admin-multi-lang-input content="settings.gdpr_message"></admin-multi-lang-input>
                </div>
            </div>

        </div>


        <div class="ui raised segment">
            <div class="ui header">Custom Code</div>
            <div class="field">
                <label>Bottom HTML (eg. Analytics)</label>
                <textarea rows="5" placeholder="<script>var hello='world'</script>" ng-model="settings.custom_html"></textarea>
            </div>
            <div class="field">
                <label>CSS</label>
                <textarea rows="5" placeholder="#id { margin: 0 }" ng-model="settings.custom_css"></textarea>
            </div>
        </div>

    </div>
</div>