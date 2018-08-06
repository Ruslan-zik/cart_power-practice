            <div class="ty-cp_delivery_date ty-reward-group">
                <label class="ty-control-group__label" for="cp_delivery_date">{(__("cp_delivery_date.cp_delivery_date"))}</label>
                <div class="ty-control-group__item">
                    {include file="common/calendar.tpl" date_id="cp_delivery_date" date_name="product_data[$obj_id][extra][cp_delivery_date]" date_val=$smarty.const.TIME start_year=$smarty.now|date_format:"%Y"}
                </div>
            </div>