/* Made with love by @fitri
 This is a component of my ReactJS project
 https://codepen.io/fitri/full/oWovYj/ */

 function enableDragSort(listClass) {
    const sortableLists = document.getElementsByClassName(listClass);
    Array.prototype.map.call(sortableLists, (list) => {enableDragList(list)});
  }
  
  function enableDragList(list) {
    Array.prototype.map.call(list.children, (item) => {enableDragItem(item)});
  }
  
  function enableDragItem(item) {
    item.setAttribute('draggable', true)
    item.ondrag = handleDrag;
    item.ondragend = handleDrop;
  }
  
  function handleDrag(item) {
    const   selectedItem = item.currentTarget,
            list = selectedItem.parentNode,
            x = event.clientX,
            y = event.clientY;
  
    selectedItem.classList.add('drag-sort-active');

    let swapItem = document.elementFromPoint(x, y) === null ? selectedItem : document.elementFromPoint(x, y);
  
    if (list === swapItem.parentNode.parentNode.parentNode) {
      swapItem = swapItem.parentNode.parentNode !== selectedItem.nextSibling ? swapItem.parentNode.parentNode : swapItem.parentNode.parentNode.nextSibling;
      list.insertBefore(selectedItem, swapItem);
    }
  }
  
  function handleDrop(item) {
    item.target.parentNode.classList.remove('drag-sort-active');
  }
  
  //(()=> {enableDragSort('drag-sort-enable')})();
  