<?php
/**
 * Created by PhpStorm.
 * User: emmaus
 * Date: 03/04/19
 * Time: 07:54
 */
?>

<!-- The Modal -->
<div class="modal fade" id="abortModal" style="top:30%;">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h3 class="modal-title">{{$title}}</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body" style="text-align:center;">
                {{$body}}
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <table class="table">
                    <tr>
                        <td class="col-lg-4 col-md-5 col-sm-6 pull-left" style="padding:5px 0px 5px 0px; border:0px solid;">
                            <a class="btn btn-theme05 btn-block" data-dismiss="modal" onclick="abortAction(0)"> {{ __('NO') }} </a>
                        </td>
                        <td class="col-lg-4 col-md-5 col-sm-6 pull-right" style="padding:5px 0px 5px 0px; border:0px solid;">
                            <a class="btn btn-theme btn-block" data-dismiss="modal" onclick="abortAction(1)"> {{ __('YES') }} </a>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>
