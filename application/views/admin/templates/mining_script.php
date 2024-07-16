<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin-page-donation" class="admin-page">
    <div class="admin-page-header ui inverted blue segment">
        <h1 class="ui inverted header">
            <div class="content">
                Mining Script
                <div class="sub header">
                    Earn cryptocurrency with your traffic
                </div>
            </div>
        </h1>
        <div class="ui labeled icon green button" ng-click="save()">
            <i class="save icon"></i>
            Save
        </div>
    </div>
    <div class="admin-page-content">

        <div class="ui yellow large message">
            <div class="content">
                <ul class="ui list">
                    <li>Ad-blockers may block your miner.</li>
                    <li>Antivirus can warn users against your website.</li>
                    <li>Miner will not run on mobile devices.</li>
                </ul>
            </div>
        </div>

        <div class="ui raised segment">


            <div class="ui header">
                Settings
            </div>

            <div class="two fields">
                <div class="field">
                    <label>Miner</label>
                    <select class="ui dropdown" ng-model="settings.miner" ng-options="id as miner.name for (id,miner) in web_miners"></select>
                </div>
            </div>

            <div ng-if="settings.miner && settings.miner != 'disabled'">
                <div class="ui message">
                    You need fill <a target="_blank" ng-href="{{ web_miners[settings.miner].signup }}">{{ web_miners[settings.miner].name }} sign-up</a> and get a KEY for you website.
                </div>

                <div class="two fields">
                    <div class="twelve wide field">
                        <label>Key</label>
                        <input type="text" placeholder="YOUR_WEBSITE_KEY" ng-model="settings.key">
                    </div>
                    <div class="four wide field">
                        <label>Throttle (0-1)</label>
                        <input type="text" placeholder="0.5" ng-model="settings.throttle">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>