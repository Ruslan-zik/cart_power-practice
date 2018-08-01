            <div class="ty-delivery_date ty-reward-group">
                <label class="ty-control-group__label" for="delivery_date">{(__("delivery_date.delivery_date"))}</label>
                <div class="ty-control-group__item">
                    {include file="common/calendar.tpl" date_id="delivery_date" date_name="product_data[$obj_id][extra][delivery_date]" date_val=$smarty.const.TIME start_year=$smarty.now|date_format:"%Y"}
                </div>
            </div>