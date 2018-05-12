using UnityEngine.UI;
using System.Collections.Generic;
using UnityEngine;

public class StatisticCell : MonoBehaviour {
	[SerializeField] Text title;
	[SerializeField] Text duration;
	[SerializeField] Text painLevel;
	[SerializeField] Text date;

	public void InitCell(string sessionTitle, string duration, string painLevel, string date) {
		this.title.text = sessionTitle;
		this.duration.text = duration;
		this.painLevel.text = painLevel;
		this.date.text = date;
	}

}
