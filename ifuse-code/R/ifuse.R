require('methods')

# parse input parameters
args <- commandArgs(trailingOnly = TRUE)
filein <- args[1]
fileout <- args[2]
hgfile <- args[3]

ogdata<-read.delim(filein,sep="\t",quote="",row.names=1,stringsAsFactors=F)



############################################################
##PRELIMINARY FUNCTIONS#####################################
############################################################

## find start and end positions of junction sequence
startend<-function(data){
	data$LeftStart<-NA
	data$LeftEnd<-NA
	data$RightStart<-NA
	data$RightEnd<-NA

	for(i in 1:nrow(data)){
		if(data$LeftStrand[i]=="+"){ 
			data$LeftStart[i]<-data$LeftPosition[i]-data$LeftLength[i]
			data$LeftEnd[i]<-data$LeftPosition[i]
		} else {
			data$LeftStart[i]<-data$LeftPosition[i]
			data$LeftEnd[i]<-data$LeftPosition[i]+data$LeftLength[i]
		}
		if(data$RightStrand[i]=="+"){
			data$RightStart[i]<-data$RightPosition[i]
			data$RightEnd[i]<-data$RightPosition[i]+data$RightLength[i]
		} else {
			data$RightStart[i]<-data$RightPosition[i]-data$RightLength[i]
			data$RightEnd[i]<-data$RightPosition[i]
		}
	}
	
return(data)
}


## fills up ragged arrays in a list 	
fillup<-function(list1){
	maxLen1 <- max(sapply(list1, length))
	newM1 <- lapply(list1, function(.ele){
		c(.ele, rep(NA, maxLen1))[1:maxLen1]})
	filled<-do.call(rbind, newM1)
	
return(filled)
}


## find genes containing left position
subsetintleft<-function(x,y){ 
	## x is  element of list of ucsc tables per chromosome
	## y is input data with start and ends 
	leftkeep<-list()
	chrmatch<-y[y$LeftChr==unique(x$chrom),]
	
	if(nrow(chrmatch)>0){ 
		for(i in 1:nrow(chrmatch)){
			if(chrmatch$LeftStrand[i]=="+"){
				lk<-x[which(x$txStart<chrmatch$LeftEnd[i] & 
				x$txEnd>chrmatch$LeftEnd[i]),] 
				## finding which intervals surround the left position
			} else lk<-x[which(x$txStart<chrmatch$LeftStart[i] & x$txEnd>chrmatch$LeftStart[i]),]
			if(length(grep("NR",lk[,2]))>0) lk<-lk[-c(grep("NR",lk[,2])),]
			if(nrow(lk)>1) lk<-lk[-c(grep("NM_0011",lk[,2])),]
			if(nrow(lk)>1 & length(unique(lk$name2))==1) lk<-lk[1,]
			leftkeep[[i]]<-lk
		}
		names(leftkeep)<-row.names(chrmatch)
		leftkeep<-do.call(rbind,leftkeep)
		colnames(leftkeep)<-paste("Left",colnames(leftkeep),sep=".")
	} else leftkeep<-NA
	
return(leftkeep)
}


## find genes containing right position
subsetintright<-function(x,y){ 
	## x is  element of list of ucsc tables per chromosome
	## y is input data with start and ends 
	rightkeep<-list()
	chrmatch<-y[y$LeftChr==unique(x$chrom),]
	
	if(nrow(chrmatch)>0){ 
		for(i in 1:nrow(chrmatch)){
			if(chrmatch$RightStrand[i]=="+"){
				rk<-x[which(x$txStart<chrmatch$RightStart[i] & 
				x$txEnd>chrmatch$RightStart[i]),]
				## finding which intervals surround the right position
			} else rk<-x[which(x$txStart<chrmatch$RightEnd[i] & x$txEnd>chrmatch$RightEnd[i]),]
			if(length(grep("NR",rk[,2]))>0) rk<-rk[-c(grep("NR",rk[,2])),]
			if(nrow(rk)>1) rk<-rk[-c(grep("NM_0011",rk[,2]),grep("NR",rk[,2])),]
			if(nrow(rk)>1 & length(unique(rk$name2))==1) rk<-rk[1,]
			rightkeep[[i]]<-rk
		}
		names(rightkeep)<-row.names(chrmatch)
		rightkeep<-do.call(rbind,rightkeep)
		colnames(rightkeep)<-paste("Right",colnames(rightkeep),sep=".")
		} else rightkeep<-NA
		
return(rightkeep)
}


## find associated junctions within gene and per chromosome
cjunct<-function(y,genes,cdsinfo){
	## y = junctions
	y<-y[,c(1:4,6:8)]
	x<-cbind(genes[,-1],cdsinfo[,c(3,4,6,7,9,14,15,17,18,20)])
	z<-cbind(y,x)
	matches<-list()

	for(i in 1:nrow(z)){
		m1<-unique(z[which((z$LeftPosition>z$Left.txStart[i]&z$LeftPosition<z$Left.txEnd[i]) & 
			(levels(z$LeftChr)[z$LeftChr]==levels(z$Left.chrom[i])[z$Left.chrom[i]])),c(1:7,8:12,18:22)]) 
			## only left genes
		m2<-unique(z[which((z$RightPosition>z$Right.txStart[i]&z$RightPosition<z$Right.txEnd[i]) & 
			(levels(z$RightChr)[z$RightChr]==levels(z$Right.chrom[i])[z$Right.chrom[i]])),c(1:7,13:17,23:27)]) 
			## only right genes
		n<-x[i,]
		if(nrow(m1)==0){
			m1[1:nrow(m2),]<-NA
			m1[,1:7]<-m2[,1:7]
		}
		if(nrow(m2)==0){
			m2[1:nrow(m1),]<-NA
			m2[,1:7]<-m1[,1:7]
		}
		mlr<-merge(m1,m2,by=1:7)
		matches[[i]]<-mlr[!is.na(mlr[,1]),]
		if(nrow(matches[[i]])!=0) matches[[i]]$associated<-paste("aj",i,sep="")
	}

	matchfirst<-do.call(rbind,matches)
	matcheds1<-matchfirst[order(matchfirst$Left.name2),]
	matcheds2<-matcheds1[order(matcheds1$Right.name2),]
	if(nrow(matcheds2)>0){
		nodup<-which(duplicated(matcheds2[,-28]))
		return(matcheds2[-nodup,])
	} else{
		z$associated<-NA
		return(z)
	}
	
}


## find junctions that share genes
gjunct<-function(matched){
	lri<-list()

	for(i in 1:nrow(matched)){
		ln<-matched$Left.name2[i]
		rn<-matched$Right.name2[i]
		if(!is.na(ln) | !is.na(rn)){ 
			t1<-matched[which((matched$Left.name2==ln 
				| matched$Right.name2==ln) | (matched$Left.name2==rn 
				| matched$Right.name2==rn)),] 
			t2<-c(t1$Left.name2,t1$Right.name2)
			t2<-t2[!is.na(t2)]
			lri[[i]]<-which(matched$Left.name2%in%t2 | matched$Right.name2%in%t2)
		} else lri[[i]]<-i
	}
	
	#lri<-unique(lri[!is.na(lri)])
	names(lri)<-paste("sg",1:length(lri),sep="")
	lri.df<-fillup(lri)
	lri.df<-unique(lri.df)
	sg<-c()
	for(k in 1:nrow(lri.df)){
		x<-lri.df[k,]
		x<-x[!is.na(x)]
		sg[x]<-row.names(lri.df)[k]
	}
	matched$sharedgenes<-sg
	
return(matched)
}


## annotate genes/junctions with event calls
annotate<-function(data){
	genemismatch<-c()
	singleevent<-c()
	fusiongene<-c()

	for(i in 1:nrow(data)){
		if(!is.na(data$Left.name2[i]) & !is.na(data$Right.name2[i])){ 
			if(data$Left.name2[i]==data$Right.name2[i]){
				genemismatch[i]<-"no"
			} else genemismatch[i]<-"yes" 
		} else genemismatch[i]<-"NA"

		if(data$LeftStrand[i]=="+" & data$RightStrand[i]=="+"){
			if(data$LeftChr[i]==data$RightChr[i]){
				singleevent[i]<-"deletion"
			} else singleevent[i]<-"interchromosomal"
			if(is.na(data$Left.strand[i]) | is.na(data$Right.strand[i])){ 
				fusiongene[i]<-NA 
			} else if(data$Left.strand[i]=="+" & data$Right.strand[i]=="+"){ 
				fusiongene[i]<-"same orientation"
			} else if(data$Left.strand[i]=="-" & data$Right.strand[i]=="-"){
				fusiongene[i]<-"same orientation"
			} else fusiongene[i]<-"opposing"
		}
		
		if(data$LeftStrand[i]=="+"&data$RightStrand[i]=="-"){
			if(data$LeftChr[i]==data$RightChr[i]){
				singleevent[i]<-"inversion"
			} else singleevent[i]<-"interchromosomal"
			if(is.na(data$Left.strand[i]) | is.na(data$Right.strand[i])){
				fusiongene[i]<-NA
			} else if(data$Left.strand[i]=="+" & data$Right.strand[i]=="-"){
				fusiongene[i]<-"same orientation"
			} else if(data$Left.strand[i]=="-" & data$Right.strand[i]=="+"){
				fusiongene[i]<-"same orientation"
			} else fusiongene[i]<-"opposing"
		}
		
		if(data$LeftStrand[i]=="-"&data$RightStrand[i]=="+"){
			if(data$LeftChr[i]==data$RightChr[i]){
				singleevent[i]<-"inversion"
			} else singleevent[i]<-"interchromosomal"
			if(is.na(data$Left.strand[i]) | is.na(data$Right.strand[i])){ 
				fusiongene[i]<-NA
			} else if(data$Left.strand[i]=="+" & data$Right.strand[i]=="-"){ 
				fusiongene[i]<-"same orientation"
			} else if(data$Left.strand[i]=="-" & data$Right.strand[i]=="+"){
				fusiongene[i]<-"same orientation"
			} else fusiongene[i]<-"opposing"
		}
		
		if(data$LeftStrand[i]=="-"&data$RightStrand[i]=="-"){
			if(data$LeftChr[i]==data$RightChr[i]){
				singleevent[i]<-"inversion or translocation"
			} else singleevent[i]<-"interchromosomal"
			if(is.na(data$Left.strand[i]) | is.na(data$Right.strand[i])){ 
				fusiongene[i]<-NA
			} else if(data$Left.strand[i]=="+" & data$Right.strand[i]=="+"){ 
				fusiongene[i]<-"same orientation"
			} else if(data$Left.strand[i]=="-" & data$Right.strand[i]=="-"){
				fusiongene[i]<-"same orientation"
			} else fusiongene[i]<-"opposing"
		}
	}
	data$genemismatch<-genemismatch
	data$singleevent<-singleevent
	data$fusiongene<-fusiongene
	
return(data)
}


## matching function for related junctions (see below)
matchfun<-function(x){
	matchlocs<-list()
	for(m in 1:length(x)){
		matchloc<-c()
		for(j in 1:length(x)){
			if(any(x[[j]]%in%x[[m]])){
				matchloc<-c(matchloc,j)
			}
		}
		matchlocs[[m]]<-matchloc
	}
	
return(matchlocs)
}


## find related junctions
rjunct<-function(z,distance){
	matches<-list()
	
	for(i in 1:nrow(z)){
		m1<-which((abs(z$'Junction LeftPosition'-z$'Junction LeftPosition'[i])<distance) & 
			(z$'Junction LeftChr'==z$'Junction LeftChr'[i]))
		m2<-which((abs(z$'Junction RightPosition'-z$'Junction RightPosition'[i])<distance) & 
			(z$'Junction RightChr'==z$'Junction RightChr'[i])) 
		m3<-which((abs(z$'Junction RightPosition'-z$'Junction LeftPosition'[i])<distance) & 
			(z$'Junction RightChr'==z$'Junction LeftChr'[i])) 
		matches[[i]]<-sort(unique(c(m1,m2,m3)))
	}
	
	matchlocs1<-matchfun(matches)
	matchlocs2<-matchfun(matchlocs1)
	umatches<-unique(matchlocs2)
	related<-c()		
	for(k in 1:length(umatches)){
		x<-umatches[[k]]
		related[x]<-paste("rj",k,sep="")		
	}
	z$'Related Junctions'<-related

return(z)
}

## find which junctions are in exons
exonannot<-function(allwithint){
	exoncds<-list()
	
	for(i in 1:nrow(allwithint)){
		x<-allwithint[i,]
		leftposincds<-rightposincds<-leftposinexon<-rightposinexon<-c()
		if(!is.na(x[16])){
			if(x[29] >= x[14] & x[29] <= x[15]){ 
				leftposincds<-"yes"
			} else leftposincds<-"no" #14 15 #left cds #29 30 31 #left position, start, end 
		lexon<-x[16:17] #16 17 #left exon
		lexon_se<-cbind(strsplit(as.matrix(lexon[1]),"\\,")[[1]],strsplit(as.matrix(lexon[2]),"\\,")[[1]])
		lexon_se<-matrix(as.numeric(lexon_se),ncol=2)
		leftiexon<-c()
		for(j in 1:nrow(lexon_se)){
			if(x[29] >= lexon_se[j,1] & x[29] <= lexon_se[j,2]){ 
				leftiexon[j]<-T
			} else leftiexon[j]<-F
		}
		if(any(leftiexon==T)) leftposinexon<-"yes" else leftposinexon<-"no"
		} else leftposinexon<-leftposincds<-NA
		
		if(!is.na(x[26])){
			if(x[34] >= x[23] & x[34] <= x[24]){
				rightposincds<-"yes"
			} else rightposincds<-"no" #23 24 #right cds	#34 35 36 #right position, start, end 
		rexon<-x[25:26] #25 26 #right exon
		rexon_se<-cbind(strsplit(as.matrix(rexon[1]),"\\,")[[1]],strsplit(as.matrix(rexon[2]),"\\,")[[1]])
		rexon_se<-matrix(as.numeric(rexon_se),ncol=2)
		rightiexon<-c()
		for(j in 1:nrow(rexon_se)){ 
			if(x[34] >= rexon_se[j,1] & x[34] <= rexon_se[j,2]){
				rightiexon[j]<-T
			} else rightiexon[j]<-F
		}
		if(any(rightiexon==T)) rightposinexon<-"yes" else rightposinexon<-"no"
		} else rightposinexon<-rightposincds<-NA
		exoncds[[i]]<-cbind(leftposincds,rightposincds,leftposinexon,rightposinexon)
	}

	exoncds_df<-do.call(rbind,exoncds)
	colnames(exoncds_df)<-c("Left Position in CDS","Right Position in CDS","Left Position in Exon", "Right Position in Exon")

return(exoncds_df)
}
############################################################
############################################################


setClass("cgsv",representation(assemseq="data.frame", 
	transseq="data.frame",intervals="data.frame",
	genes="data.frame",cggenes="data.frame",junctions="data.frame",
	othercg="data.frame",otherannot="data.frame"))






############################################################
##INTERVALS OF LEFT AND RIGHT SECTIONS######################
############################################################
ogdata_se<-startend(ogdata)
print("start ends found")
gc()
############################################################
############################################################


############################################################
##FINDING REGIONS###########################################
############################################################
##with genome table
ucscgenes<-read.table(hgfile,header=T,sep="\t",stringsAsFactors=F)
ucscgenes_list<-list()
chrnames<-c(paste("chr",1:22,sep=""),"chrX","chrY")
for(i in 1:24){
	ucscgenes_list[[i]]<-ucscgenes[ucscgenes$chrom==chrnames[i],]
}
print("ucsc ranges found")
gc()
############################################################


############################################################
##FINDING GENE REGIONS######################################
############################################################
##obtaining intervals (left, right positions) of genes that mapped
leftints<-lapply(ucscgenes_list,subsetintleft,ogdata_se)
rightints<-lapply(ucscgenes_list,subsetintright,ogdata_se)
leftints.df<-do.call(rbind,leftints[!is.na(leftints)])
rightints.df<-do.call(rbind,rightints[!is.na(rightints)])
if(length(grep("\\.",row.names(leftints.df)))>0){
	leftints.df$CG.ID<-sapply(strsplit(row.names(leftints.df),"\\."),"[",1)
	} else leftints.df$CG.ID<-row.names(leftints.df)
if(length(grep("\\.",row.names(rightints.df)))>0){
	rightints.df$CG.ID<-sapply(strsplit(row.names(rightints.df),"\\."),"[",1)
	} else rightints.df$CG.ID<-row.names(rightints.df)
allannot<-merge(leftints.df,rightints.df,by=17,all=T)
allannot.cg<-merge(ogdata_se,allannot,by.x="row.names",by.y=1,all=T)
colnames(allannot.cg)[1]<-"CG.ID"
print("gene regions found")
############################################################
############################################################


############################################################
##EVENTS####################################################
############################################################
data.slot<-new("cgsv",
	assemseq=data.frame(CG.ID=allannot.cg[,1],AssembledSequence=allannot.cg$AssembledSequence),
	transseq=data.frame(CG.ID=allannot.cg[,1],TransitionSequence=allannot.cg$TransitionSequence),
	intervals=allannot.cg[,c(1,which(colnames(allannot.cg)%in%c("LeftStart","LeftEnd","RightStart","RightEnd")))],
	genes=allannot.cg[,c(1,which(colnames(allannot.cg)%in%c("Left.name",
		"Left.chrom","Left.strand","Left.txStart","Left.txEnd","Right.name",
		"Right.chrom","Right.strand","Right.txStart","Right.txEnd")))],
	cggenes=allannot.cg[,c(1,which(colnames(allannot.cg)%in%c("LeftGenes","RightGenes")))],
	junctions=allannot.cg[,c(1,which(colnames(allannot.cg)%in%c("LeftChr",
        "LeftPosition","LeftStrand","LeftLength","RightChr","RightPosition",
        "RightStrand","RightLength","StrandConsistent","Interchromosomal",
        "Distance","TransitionLength")))],
	othercg=allannot.cg[,c(1,which(colnames(allannot.cg)%in%c("DiscordantMatePairAlignments",
		"JunctionSequenceResolved","LeftRepeatClassification","RightRepeatClassification",
        "XRef","DeletedTransposableElement",
		"KnownUnderrepresentedRepeat","FrequencyInBaselineGenomeSet")))],
	otherannot=allannot.cg[,c(1,which(colnames(allannot.cg)%in%c("Left.bin","Left.cdsStart",
        "Left.cdsEnd","Left.exonCount","Left.exonStarts","Left.exonEnds",
        "Left.score","Left.name2","Left.cdsStartStat","Left.cdsEndStat",
        "Left.exonFrames","Right.bin","Right.cdsStart",
        "Right.cdsEnd","Right.exonCount","Right.exonStarts","Right.exonEnds",
        "Right.score","Right.name2","Right.cdsStartStat","Right.cdsEndStat",
        "Right.exonFrames")))])

matched<-cjunct(data.slot@junctions,data.slot@genes,data.slot@otherannot)
gc()
print("associated junctions found")
	
matchedgenes<-gjunct(matched)
gc()
print("shared genes found")
############################################################
############################################################


############################################################
##ANNOTATION################################################
############################################################
datasgannot<-annotate(matchedgenes)
gc()
print("fusion events annotated 1")

allwithint<-merge(datasgannot,unique(data.slot@intervals),by=1)
names(allwithint)[c(1:7,33:36)]<-paste("Junction",names(allwithint)[c(1:7,33:36)],sep=" ")
names(allwithint)[8:27]<-paste("Gene",names(allwithint)[8:27],sep=" ")
names(allwithint)[28:32]<-c("Associated Junctions","Shared Genes","Gene Mismatch","Single Event","Fusion Gene")

allwithint<-allwithint[,c(1,28:32,22,27,8:12,18:21,13:17,23:26,
	2,4,3,33,34,5,7,6,35,36)] ## just putting in a nicer order

## annotate with in exon yes/no
exoncds_df<-exonannot(allwithint)
geneannotwithexoncds<-cbind(allwithint,exoncds_df)
geneannotwithexoncds<-geneannotwithexoncds[,c(1:6,37:40,7:36)] ## just putting in a nicer order
print("fusion events annotated 2")
gc()

## find related junctions per chromosome
output<-rjunct(geneannotwithexoncds,100)
output<-output[,c(1,41,2:40)]
gc()

## add length, sequence
final1<-merge(output,ogdata[,c(4,8,15,14,24)],by.x=1,by.y="row.names")
names(final1)[42:46]<-paste("Junction",names(final1)[42:46],sep=" ")

## write output
if(length(grep(".csv",fileout))>0){
	write.csv(final1,fileout,row.names=F)
} else write.table(final1,fileout,row.names=F,quote=F,sep="\t")

print("program done")
############################################################
############################################################


