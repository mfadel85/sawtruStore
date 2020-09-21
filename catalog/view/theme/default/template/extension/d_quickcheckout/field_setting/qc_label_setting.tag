<qc_label_setting>
    <qc_field_setting 
        if={getState().edit}
        setting_id={setting_id} 
        field_id={ opts.field_id } 
        step={ opts.step } 
        display={ opts.field.display }
        delete={opts.field.custom}
        ondelete={onDelete}>
    </qc_field_setting>

    <qc_setting 
        if={getState().edit}
        setting_id={ setting_id }
        title={ opts.field_id } 
        field={ opts.field }
        field_id={ opts.field_id }
        step={ opts.step } >
        <ul class="qc-setting-tabs ve-tab ve-tab--block">
            <li class="qc-tab ve-tab__item active">
                <a href="#{ opts.setting_id }_general" id="{ opts.setting_id }_home_tab" role="tab" data-toggle="tab" aria-controls="{ setting_id }_general" aria-expanded="true">{getLanguage().general.text_general}</a>
            </li>
            <!--  <li class="ve-tab__item">
                <a href="#{ opts.setting_id }_design" id="{ opts.setting_id }_design_tab" role="tab" data-toggle="tab" aria-controls="{ opts.setting_id }_design" aria-expanded="true">{getLanguage().general.text_design}</a>
            </li>  -->
        </ul>

        <div class="qc-setting-tab-content"> 
            <div class="qc-setting-tab-pane fade in active" role="tabpanel" id="{ opts.setting_id }_general" aria-labelledby="{ opts.setting_id }_general_tab"> 
                <div class="ve-editor__setting__content__section">
                    <div class="ve-field">
                        <label class="ve-label">{getLanguage().general.text_display}</label>
                        <div>
                            <table class="ve-table ve-table--bordered">
                                <thead>
                                    <tr>
                                        <th>{getLanguage().general.text_guest}</th>
                                        <th>{getLanguage().general.text_register}</th>
                                        <th>{getLanguage().general.text_logged}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                        <qc_switcher 
                                            if={!isEmpty(getState().config.guest[opts.step].fields[opts.field_id].display)}
                                            onclick="{parent.edit}" 
                                            name="config[guest][{opts.step}][fields][{ opts.field_id }][display]" 
                                            data-label-text="Enabled" 
                                            value={ getState().config.guest[opts.step].fields[opts.field_id].display } 
                                        /></td>
                                        <td><qc_switcher 
                                            if={!isEmpty(getState().config.register[opts.step].fields[opts.field_id].display)}
                                            onclick="{parent.edit}" 
                                            name="config[register][{opts.step}][fields][{ opts.field_id }][display]" 
                                            data-label-text="Enabled" 
                                            value={ getState().config.register[opts.step].fields[opts.field_id].display } 
                                        /></td>
                                        <td><qc_switcher 
                                            if={!isEmpty(getState().config.logged[opts.step].fields[opts.field_id].display)}
                                            onclick="{parent.edit}" 
                                            name="config[logged][{opts.step}][fields][{ opts.field_id }][display]" 
                                            data-label-text="Enabled" 
                                            value={ getState().config.logged[opts.step].fields[opts.field_id].display } 
                                        /></td>
                                    </tr>
                                
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="ve-field">
                        <label class="ve-label">{getLanguage().general.text_title}</label>
                        <div class="ve-input-group">
                            <span class="ve-input-group__addon">
                                <img src="{getLanguage().general.img}">
                            </span>
                            <input onchange="{parent.edit}" type="text" class="ve-input" name="language[{ opts.step }][{ opts.field.text }]" value={ getLanguage()[opts.step][opts.field.text] } />
                        </div>
                    </div>
                </div>
            </div>

            <!-- <div class="qc-setting-tab-pane fade" role="tabpanel" id="{ opts.setting_id }_design" aria-labelledby="{ opts.setting_id }_design_tab">
                <design_setting></design_setting>
            </div>  -->
        </div>

    </qc_setting>
    
    <script>
        this.mixin({store:d_quickcheckout_store});
        this.setting_id = opts.step +'_'+ opts.field_id +'_setting';

        var tag = this;

        edit(e){
            this.store.dispatch(this.opts.step+'/edit', $('#'+ tag.setting_id).find('form').serializeJSON());
        }

        onDelete(){
            this.opts.ondelete();
        }
    </script>
</qc_label_setting>