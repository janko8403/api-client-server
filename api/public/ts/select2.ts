// // @ts-nocheck
//
// /***** rozszerzenie select2 *****/
//
// $.fn.select2.amd.define('select2/selectAllAdapter', [
//     'select2/utils',
//     'select2/dropdown',
//     'select2/dropdown/attachBody',
// ], function (Utils, Dropdown, AttachBody) {
//
//     function SelectAll() {
//     }
//
//     SelectAll.prototype.render = function (decorated) {
//         var self = this,
//             $rendered = decorated.call(this),
//             $selectAll = $(
//                 '<button class="btn btn-xs btn-default" type="button" style="margin-left:6px;"><i class="fa fa-check-square-o"></i> ' + t.t('zaznacz') + '</button>',
//             ),
//             $unselectAll = $(
//                 '<button class="btn btn-xs btn-default" type="button" style="margin-left:6px;"><i class="fa fa-square-o"></i> ' + t.t('odznacz') + '</button>',
//             ),
//             $btnContainer = $('<div style="margin: 5px 0;">').append($selectAll).append($unselectAll)
//
//         if (!this.$element.prop('multiple')) {
//             // this isn't a multi-select -> don't add the buttons!
//             return $rendered
//         }
//
//         $rendered.find('.select2-dropdown').prepend($btnContainer)
//         $selectAll.on('click', function (e) {
//             var $results = $rendered.find('.select2-results__option[aria-selected=false]')
//             $results.each(function () {
//                 self.trigger('select', {
//                     data: $(this).data('data'),
//                 })
//             })
//             self.trigger('close')
//         })
//         $unselectAll.on('click', function (e) {
//             var $results = $rendered.find('.select2-results__option[aria-selected=true]')
//             $results.each(function () {
//                 self.trigger('unselect', {
//                     data: $(this).data('data'),
//                 })
//             })
//             self.trigger('close')
//         })
//         return $rendered
//     }
//
//     function ShowCount() {
//     }
//
//     ShowCount.prototype.render = function (decorated) {
//         var self = this,
//             $rendered = decorated.call(this),
//             $select = $(self.$element[0])
//
//         if (!$select.prop('multiple')) {
//             // this isn't a multi-select -> don't add the buttons!
//             return $rendered
//         }
//
//         $select.on('change', function () {
//             var uldiv = $select.siblings('span.select2').find('ul')
//             var count = $select.find(':selected').length
//             var html = count > 0 ? '<span class=\'select2-number-selected\'>' + t.t('Wybrano') + ': ' + count + '</span>' : ''
//
//             $rendered.find('.select2-number-selected').remove()
//             $rendered.find('.select2-dropdown').prepend(html)
//         })
//
//         return $rendered
//     }
//
//     return Utils.Decorate(
//         Utils.Decorate(
//             Utils.Decorate(
//                 Dropdown,
//                 AttachBody,
//             ),
//             SelectAll,
//         ),
//         ShowCount,
//     )
// })