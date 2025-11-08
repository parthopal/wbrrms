function TreeView(datas, options) {
    this.root = document.createElement("div");
    this.root.className = "treeview";
    let t = this;



    var defaultOptions = {
        showAlwaysCheckBox: true,
        fold: true,
        openAllFold:false
    }

    options = Object.assign(defaultOptions, options);


    // GROUP EVENTS ---------------------

    function groupOpen() {
        $(this).parent().find(">.group").slideDown("fast");
    }
    function groupClose() {
        $(this).parent().find(">.group").slideUp("fast");
    }
    function groupToggle() {
        $(this).parent().find(">.group").slideToggle("fast");
    }



    // ITEM EVENTS --------------------
    function changeCheckState(value, allChildCheck) {
        var c = this.checked;
        if (value == null || value instanceof MouseEvent) { // TOGGLE CHECK
            if (c == 0) c = 1;
            else if (c == 1) c = 0;
            else if (c == 2) c = 1;
        } else {
            c = value;
        }
        this.checked = c;
        setCheckState.bind(this)(c);
        if (c !== 2)
            checkAllChilds.bind(this)(c);
        checkControlParents.bind(this)();
    }
    
    function changeCheckStateView(value, allChildCheck) {
        var c = this.checked;
        if (value === null || value instanceof MouseEvent) { // TOGGLE CHECK
            if (c === 0) c = 1;
            else if (c === 1) c = 0;
            else if (c === 2) c = 1;
        } else {
            c = value;
        }
        this.checked = c;
        setCheckStateView.bind(this)(c);
        if (c !== 2)
            checkAllChildsView.bind(this)(c);
        checkControlParentsView.bind(this)();
    }

    function checkAllChilds(value) {
        var $group = $(this).parent(".group");
        $group.find(".item").each(function (index, el) {
            setCheckState.bind(el)(value)
        })
    }
    
    function checkAllChildsView(value) {
        var $group = $(this).parent(".group");
        $group.find(".view").each(function (index, el) {
            setCheckStateView.bind(el)(value)
        })
    }

    function checkControlParents() {
        var $parents = $(this).parents(".treeview .group");

        for (var index = 1 ; index < $parents.length ; index++) {
            var el = $parents[index];
            item = $(el).find(">.item").get(0);
            $children = $(el).find(".group .item");
            var all1 = true;
            var all0 = true;
            for (var i = 0; i < $children.length; i++) {
                if ($children[i].checked != 1) all1 = false;
                if ($children[i].checked != 0) all0 = false;
            }
            if (all1) setCheckState.bind(item)(1);
            else if (all0) setCheckState.bind(item)(0);
            else setCheckState.bind(item)(2);
        }
    }
    
    function checkControlParentsView() {
        var $parents = $(this).parents(".treeview .group");

        for (var index = 1 ; index < $parents.length ; index++) {
            var el = $parents[index];
            view = $(el).find(">.view").get(0);
            $children = $(el).find(".group .view");
            var all1 = true;
            var all0 = true;
            for (var i = 0; i < $children.length; i++) {
                if ($children[i].checked !== 1) all1 = false;
                if ($children[i].checked !== 0) all0 = false;
            }
            if (all1) setCheckStateView.bind(view)(1);
            else if (all0) setCheckStateView.bind(view)(0);
//            else setCheckStateView.bind(view)(2);
        }
    }

    function setCheckState(value) {
        this.checked = value
        this.setAttribute("check-value", value)
        if (value == 0) {
            $(this).find(">[check-icon]")[0].className = "far fa-circle";
        }
        if (value == 1) {
            $(this).find(">[check-icon]")[0].className = "fas fa-check-circle";
        }
        if (value == 2) {
            $(this).find(">[check-icon]")[0].className = "fas fa-dot-circle";
        }
    }
    
    function setCheckStateView(value) {
        this.checked = value
        this.setAttribute("check-value", value)
        if (value == 0) {
            $(this).find(">[check-icon]")[0].className = "far fa-square";
        }
        if (value == 1) {
            $(this).find(">[check-icon]")[0].className = "fas fa-check-square";
        }
//        if (value == 2) {
//            $(this).find(">[check-icon]")[0].className = "fas fa-dot-square";
//        }
    }

    /* FIRST CREATION */

    function createTreeViewReq(parentNode, datas, options) {
//        console.log("datas len:",datas.length, "datas:",datas);
        for (var i = 0; i < datas.length; i++) {
            if (datas[i] != null) {
                //console.log("datas i:", i, "data:", datas)
                var data = datas[i];
                var item = createSingleItem(data);
                parentNode.appendChild(item);
                if ("children" in data && data.children.length > 0) {
                    createTreeViewReq(item, data.children, options)
                }
            }
        }
    }

    function createSingleItem(data) {
//        console.log('createSingleItem');
        var group = document.createElement("p");
        group.className = "group"
        if ("className" in options)
            group.className += options.className;

        if ("fold" in options) {
            var foldButton = document.createElement("i");
            foldButton.className = "fa fa-caret-right";
            foldButton.setAttribute("fold-button", 1);
           
            foldButton.onclick = groupToggle.bind(foldButton);

            foldButton.isOpened = options.fold;
            
            group.appendChild(foldButton)
        }

        // ALERT ADD ICON
        var item = document.createElement("span");
        item.className = "item";
        item.innerHTML = data.text;
        item.data = data;
        for (var keys = Object.keys(data), i = 0; i < keys.length ; i++) {
            item.setAttribute("data-" + keys[i], data[keys[i]]);
        }
        if ("checked" in data || options.showAlwaysCheckBox == true) {
            var checked = document.createElement("i");
            checked.setAttribute("check-icon", "1");
            checked.className = "fa ";

            item.prepend(checked);

            if ("checked" in data && data.checked) {
                setCheckState.bind(item)(data.checked ? 1 : 0);
            } else {
                setCheckState.bind(item)(0);
            }

        }
        item.onclick = changeCheckState.bind(item);
        group.appendChild(item);
        //sujay
//        console.log(data.onlyView);
/*
        if("onlyView" in data) {
            var view = document.createElement('span');
            view.className = 'view';
            view.innerHTML = 'Only View';
            view.data = data;
            for (var keys = Object.keys(data), i = 0; i < keys.length ; i++) {
                if(keys[i] === 'id') {
                 view.setAttribute("data-" + keys[i], 'p_' + data[keys[i]]);   
                } else if(keys[i] === 'text') {
                    view.setAttribute("data-" + keys[i], 'Only View');   
                } else {
                 view.setAttribute("data-" + keys[i], data[keys[i]]);   
                }            
            }
//            if ("onlyView" in data) {
//            console.log('am here: onlyView');            
            var checked1 = document.createElement("i");
            checked1.setAttribute("check-icon", "1");
            checked1.className = "fa ";

            view.prepend(checked1);

            if ("onlyView" in data && data.onlyView) {
                setCheckStateView.bind(view)(data.onlyView ? 1 : 0);
            } else {
                setCheckStateView.bind(view)(0);
            }            
//            }
            view.onclick = changeCheckStateView.bind(view); //sujay        
            group.appendChild(view); //sujay
        }        
*/		
        //end
        return group;
    }




    this.update = function () { 
        $(t.root).find(".group").each(function (index, el) {
            if ($(el).find(".group").length > 0) {
                $(el).find(">[fold-button]").css("visibility", "visible");
            } else {
                $(el).find(">[fold-button]").css("visibility", "hidden");
            }
            checkControlParents.bind($(el).find(">.item"))();
//            checkControlParentsView.bind($(el).find(">.view"))();
        })

    }

    this.load = function (datas) {
        $(this.root).empty();
        createTreeViewReq(this.root, datas, options);
        this.update();
    }
    this.save = function (type, node) {
        if (type == null) type = "tree";
        if (type == "tree") {
            if (node == null) {
                var data = [];
                var $children = $(this.root).find(">.group");
                for (var i = 0; i < $children.length; i++) {
                    var child = this.save("tree", $children[i])
                    data.push(child)
                }
                return data;
            } else {
                var data = saveSingle($(node).find(">.item")[0], $(node).find(">.view")[0]);
                data.children = []
                var $children = $(node).find(">.group");

                for (var i = 0; i < $children.length; i++) {
                    var child = this.save("tree", $children[i])
                    data.children.push(child);
                }
                return data;
            }

        }

        if (type == "list") {
            var data = [];
            var $items = $(this.root).find(".item");
            for (var i = 0; i < $items.length; i++) {
                data.push(saveSingle($items[i]));
            }
            return data;
        }
    }
    function saveSingle(el, e2 = null) {
//        console.log(e1);
        if (el == null) el = this;
        if (e2 == null) e2 = this;
        ret = Object.assign(
            { children: [] },
            el.data,
            { checked: el.checked },
            {onlyView: e2.checked});
        console.log(ret);
        return ret;
    }

    this.load(datas);
    this.openAllFold = function (item) {
        if (item == null) item = this.root;
        $(item).find("[fold-button]").each(function (index, el) {
            
            groupOpen.bind(this)();
        })
    }
    this.closeAllFold = function (item) {
        if (item == null) item = this.root;
        $(item).find("[fold-button]").each(function (index, el) {

            groupClose.bind(this)();
        })
    }
    
    if (options.openAllFold) {
        this.openAllFold();
    } else {
        this.closeAllFold();
    }
    return this;

}
