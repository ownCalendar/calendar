<?php
/**
 * Calendar App
 *
 * @author Raghu Nayyar
 * @author Georg Ehrke
 * @copyright 2016 Raghu Nayyar <hey@raghunayyar.com>
 * @copyright 2016 Georg Ehrke <oc.list@georgehrke.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
?>

<div class="new-entity-container">
	<div
		class="new-entity"
		data-apps-slide-toggle=".time-accounting"
		id="time-accounting-button">
		<span class="new-entity-title" role="button"><?php p($l->t('Time Accounting')); ?></span>
	</div>
	<fieldset class="calendarlist-fieldset time-accounting hide">
		<div class="events events--fieldset">
			<div class="events events--fieldset">
				<center><span><?php p($l->t('Start:')); ?>
				<ocdatetimepicker style="width:70px;" ng-model="start" disabletime="properties.allDay" datetabindex="100" timetabindex="101" readonly="readOnly"></ocdatetimepicker></span></center>
			</div>
			<div class="events events--fieldset">
				<center><span><?php p($l->t('End:')); ?> &nbsp
				<ocdatetimepicker ng-model="end" disabletime="properties.allDay" datetabindex="105" timetabindex="106" readonly="readOnly"></ocdatetimepicker></span></center>
			</div>

			<form ng-submit="accountTime();getColor()">
			<input
				id="submitnewTime"
				class="primary accept-button"
				oc-click-slide-toggle="{
					selector: '.time-accounting',
					hideOnFocusLost: false,
					cssClass: 'closed'
				}"
				type="submit"
				value="<?php p($l->t('Calculate')); ?>" />
			</form>

			<div><center ng-if="time > 0 || allDayEvents > 0"><span><?php p($l->t('Total Time')); ?></span></center>
			<center ng-if="time != 0"><span ng-bind="time"></span></center>
			<center ng-if="allDayEvents > 0">{{allDayEvents}} All Day {{allDayEvents == 1 ? 'Event' : 'Events'}}</center>
			<center style="color: red" ng-if="time == 0 && allDayEvents == 0">No events in specified time range</center>
			<center ng-if="time > 0 || allDayEvents > 0">Total Time per Calendar Layer</center>
			<div ng-repeat="event in events"><center><b><li style='color:{{colorDict[event]}}'>{{event}} : {{calEvents[event] == undefined ? 'No time' : calEvents[event]}}{{(calAllDay[event] == 0 ? '' : (calAllDay[event] == 1 ? ', 1 all day event' : ', ' + calAllDay[event] + ' all day events'))}}</li></b></center></div>
			</div>

		</div>
	</fieldset>
</div>
