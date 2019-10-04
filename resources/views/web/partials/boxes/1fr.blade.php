<div class="box-1fr">
  <div>
    <div class="box__c">
      <div>
        @if (isset($elements[0]))
          <img src="/media/{{$elements[0]->image->name}}/lg" height="560" width="860" alt="{{$elements[0]->image->caption}}">
        @endif
      </div>
    </div>
  </div>
</div>