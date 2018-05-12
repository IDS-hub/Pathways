using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class Subscription : WindowController {
	public static System.Action OnCloseSubcription;

	[SerializeField] Text description;

	SubscrptionItem.SubscriptionType selecteType;

	void OnEnable() {
		SubscrptionItem.OnSelectSubscriptonType += SelectSubscriptonType;	
		Loader.Instane.RemoveLoading();
	}

	void OnDisable() {
		SubscrptionItem.OnSelectSubscriptonType -= SelectSubscriptonType;
	}

	void SelectSubscriptonType(SubscrptionItem.SubscriptionType select, string text) {
		selecteType = select;
		description.text = text;
	}

	public void OnContinue() {
		int typeCode = 0;

		if (selecteType == SubscrptionItem.SubscriptionType.ONE_MONTH)
			typeCode = 1;
		else if (selecteType == SubscrptionItem.SubscriptionType.ONE_YEAR)
			typeCode = 2;
		else if (selecteType == SubscrptionItem.SubscriptionType.LIFETIME)
			typeCode = 3;

		SmartIAPListener.INSTANCE.Purchase("android.test.purchased", (purchaseSuccess, purchaseToken) => {
			Debug.Log("purchaseToken "+purchaseToken);
			if(purchaseSuccess){
				Loader.Instane.ShowLoading();
				apiManager.DoSubscribeUser(purchaseToken, (jsonData, success) => {
					Loader.Instane.RemoveLoading();
					if(success){

						UserInfo.IsSubscribe = true;
						if(APIManager.OnProfileLoadComplete != null)
							APIManager.OnProfileLoadComplete();

						Popup.Instance.ShowPopup("Success!", "Purchase Complete",()=>{
							if (OnCloseSubcription != null)
								OnCloseSubcription();
							
							Destroy(gameObject);
						});
					}
				});
			}
		});
	}

	public void OnCancel() {
		if (OnCloseSubcription != null)
			OnCloseSubcription();
		Destroy(gameObject);
	}
}
