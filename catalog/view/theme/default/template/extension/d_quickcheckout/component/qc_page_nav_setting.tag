<qc_page_nav_setting>
    <form class="page-nav-setting">
        <div class="ve-btn-group ve-btn-group--sm" data-toggle="buttons">
            <a class="ve-btn ve-btn--default " onclick="{toggleSetting}" >
                <i class="fa fa-gear"></i>
            </a>

            <label class="ve-btn ve-btn--default { (opts.page.display == 1) ? 'active' : ''}" for="payment_address_display" onclick="{editCheckbox}">
                <input name="layout[pages][{opts.page_id}][display]" type="hidden" value="0">
                <input name="layout[pages][{opts.page_id}][display]" id="layout_pages_display_{opts.page_id}" type="checkbox" value="1" checked={ opts.page.display == 1 }>
                <i class="fa fa-eye"></i>
            </label>

            <a class="ve-btn ve-btn--default " onclick="{removePage}" >
                <i class="fa fa-times"></i>
            </a>
        </div>
    </form>

    <qc_setting 
        setting_id="qc_page_nav_setting_{opts.page_id}" 
        page_id="{opts.page_id}" 
        page="{opts.page}" 
        ref="setting" 
        title="Page nav" >
        <div class="ve-editor__setting__content__section">
            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_display}</label>
                <div>
                    <qc_switcher onclick="{parent.edit}" name="layout[pages][{opts.page_id}][display]" data-label-text="Enabled" value={opts.page.display} />
                </div>
            </div>

            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_title}</label>
                <div>
                    <input onchange="{parent.edit}" type="text" class="ve-input" name="layout[pages][{opts.page_id}][text]" value={ opts.page.text } />
                </div>
            </div>

            <div class="ve-field">
                <label class="ve-label">{getLanguage().general.text_description}</label>
                <div>
                    <input onchange="{parent.edit}" type="text" class="ve-input" name="layout[pages][{opts.page_id}][description]" value={ opts.page.description } />
                </div>
            </div>
        </div>
    </qc_setting>
    <script>
        this.mixin({store:d_quickcheckout_store});
        this.setting_id = 'qc_page_nav_setting_'+ opts.page_id;
        var tag = this;
        toggleSetting(e){
            if($('#'+ this.setting_id).hasClass('show')){
                this.store.hideSetting()
            }else{
                this.store.showSetting(this.setting_id);
            }
        }

        removePage(e){
            this.store.dispatch('page/remove', { page_id: this.opts.page_id });
        }

        edit(e){
            this.store.dispatch('setting/edit', $('#'+ tag.setting_id).find('form').serializeJSON());
        }

        editCheckbox(e){
            var checkbox = $(e.currentTarget).find('input[type=checkbox]');
            checkbox.prop("checked", !checkbox.prop("checked"));
            tag.store.dispatch('setting/edit', $(tag.root).find('.page-nav-setting').serializeJSON());

        }
    </script>
</qc_page_nav_setting>