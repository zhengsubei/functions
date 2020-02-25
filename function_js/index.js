/**
 * 引用类型数据的深拷贝
 * 
 * */
 
 function deepClone(data) {
    if (Array.isArray(data)) {
      return data.map(deepClone);
    } else if (isObject(data)) {
      return Object.keys(data).reduce(function(o, k) {
        o[k] = deepClone(data[k]);
        return o;
      }, {});
    } else {
      return data;
    }
  }



  /**
   * array to tree
   * 将数组数据转化成tree的数据接口 
   *
  */

  function formatDataToTree(arr, parentId) {
      var tree = [],
        mappedArr = {},
        arrElem,
        mappedElem;

        for(var i = 0,len = arr.length; i < len; i++){
            arrElem = arr[i];
            mappedArr[arrElem._id] = arrElem;
            mappedArr[arrElem._id]['childrens'] = [];
        }

        for(var id in mappedArr) {
            if(mappedArr.hasOwnProperty(id)){
                mappedElem = mappedArr[id];
                if(mappedElem[parentId]){
                    mappedArr[mappedElem[parentId]]['childrens'].push(mappedElem);
                } else {
                    tree.push(mappedElem);
                }
            }
        }
        return tree;
  }