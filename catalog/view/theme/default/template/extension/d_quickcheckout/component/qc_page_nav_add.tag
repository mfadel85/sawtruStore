<qc_page_nav_add>
    <div if={getState().edit} class="page-nav-add" >
        <a class="process-page-edit" onclick={addPage}>
            <div class="icon"><i class="fa fa-plus"></i></div>
            <div class="content">
                <div class="text">{getLanguage().general.text_add_page}</div>
                <div class="description">{getLanguage().general.text_add_page}</div>
            </div>
        </a>
    </div>

    <script>
        this.mixin({store:d_quickcheckout_store});
        addPage(){
            this.store.dispatch('page/add');
        }
    </script>
</qc_page_nav_add>