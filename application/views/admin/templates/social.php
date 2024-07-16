<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="admin-page-social" class="admin-page">

    <div class="admin-page-header ui inverted blue segment">
        <h1 class="ui inverted header">
            <div class="content">
                Social
                <div class="sub header">
                    Social media profiles
                </div>
            </div>
        </h1>
        <div class="ui green labeled icon button" ng-click="save()">
            <i class="save icon"></i>
            Save
        </div>
    </div>
    <div class="admin-page-content">

        <div class="ui raised segment">

            <div class="ui stackable two column grid">
                <div class="column" ng-repeat="(name,details) in social_networks">
                    <div class="ui fluid labeled input">
                        <div ng-class="'ui ' + details[2] + ' label'">
                            <i ng-class="details[0] + ' icon'"></i>
                            {{ details[1] }}
                        </div>
                        <input type="text" placeholder="{{ details[1] }}" ng-model="settings[name]">
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>